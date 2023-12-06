<?php

namespace App\DataTables;

use App\Models\UserDistinction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDistinctionDataTable extends DataTable
{

    protected $status;
    protected $userID;

    /**
     * @param null $status
     */
    public function __construct($status = null, $userID = null)
    {
        $this->status = $status;
        $this->userID = $userID;

    }


    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->editColumn('profile_image', function ($row) {
              return  '<img style=" width: 50px; height: 50px; "  src="'.asset($row->profile_image ?? "assets/images/demo/users/face11.jpg" ) . '" />' ;
            })

            ->editColumn('email', function ($row) {
                return '<a href="mailto:'.$row->email.'">'.$row->email.'</a>' ;
            })


            ->editColumn('mobile', function ($row) {
                $mobile = $row->country_code.$row->mobile ;
                return '<a href="tel:'.$mobile.'">'.$mobile.'</a>' ;
            })

            ->editColumn('type', function ($row) {
                return " <span class='badge bg-success bg-opacity-10 text-success'>" . __('messages.' . $row->type) . "</span>";
            })
            ->editColumn('status', function ($row) {
                return " <span class='badge bg-success bg-opacity-10 text-success'>" . __('messages.' . $row->status) . "</span>";
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->toDateString();
            })

            ->editColumn('action', function ($user) {

                $btns =" <a href='".(route('users.show' , $user))."' class='dropdown-item'> <i class='ph-eye me-2'></i> الملف الشخصي</a>";
                $btns .=" <a href='".(route('admin.distinction.show' , $user))."' class='dropdown-item'> <i class='ph-eye me-2'></i> التفاصيل والقرار</a>";
                $btns .=" <a href='".(route('users.show' , $user))."' class='dropdown-item'> <i class='ph-eye me-2'></i> سجل التمييز السابق</a>";
                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                         $btns
                        </div>
                    </div>
                </div>
                HTML;
            })

            ->rawColumns([
                'action',
                'status',
                'email',
                'mobile',
                'profile_image',
                'type',
            ])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\UserDistinction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserDistinction $model): QueryBuilder
    {
        return $model->newQuery()
            ->join('users' , 'users.id' ,  'user_distinctions.user_id')
            ->when($this->status, function ($q) {
                $q ->where( function ($q) {
                switch ($this->status){
                    case 'new';
                        $q->where('status', 'new');
                    break;
                    case 'active';
                        $q->where('status', 'active')
                        ->where('to_date' ,'>' , now()->toDateString());
                        break;
                    case 'ended';
                        $q->where('status', 'ended')
                            ->OrWhere('to_date' ,'<=' , now()->toDateString());
                        break;
                    case 'rejected';
                        $q->where('status', 'rejected');
                        break;
                }

                });
            })
            ->when($this->userID, function ($q) {
                $q->where('user_id', $this->userID);
            })
            ->whereHas('user', function ($q) {
                $q->when($this->request->filled('search_key'), function ($q) {
                    $q->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('email', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('country_code', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('mobile', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('username', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('account_comment', 'like', '%' . $this->request()->input('search_key') . '%');
                    });
                });
                $q->when($this->request->filled('country_id') || $this->request->filled('city_id'), function ($q) {
                    $r = [];
                    if ($this->request->filled('country_id')) $r['country_id'] = $this->request->country_id;
                    if ($this->request->filled('city_id')) $r['city_id'] = $this->request->country_id;
                        $q->whereRelation('userAddress', $r);
                });
                $q->when($this->request->filled('type'), function ($q) {
                    $q->where('type', $this->request->input('type'));
                }) ;
            })
            ->when($this->request->filled('from_date'), function ($q) {
                $q->where('from_date', '>=', $this->request()->input('from_date'));
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->where('to_date', '<=', $this->request()->input('to_date'));
            })
            ->when($this->request->filled('status'), function ($q) {
                $q->where('status', $this->request->input('status'));
            })

 ->select('user_distinctions.*'   ,'users.name','users.type','users.profile_image','users.email','users.mobile','users.country_code' ,'users.last_process_at')
->selectRaw('(select count(*) from `orders` where
 (user_distinctions.user_id = `orders`.`chef_id` or user_distinctions.user_id = `orders`.`delivery_id`)
 and `orders`.`deleted_at` is null) as `orders_count`')
->selectRaw('(select sum(amount) from `transfer_records` where user_distinctions.user_id = `transfer_records`.`to_id` and `to_type` in (?,?) and `transfer_records`.`deleted_at` is null) as `transfers_count`' ,['chef' ,'delivery'])
->selectRaw('(select count(*) from `sanctions` where user_distinctions.user_id = `sanctions`.`user_id`) as `sanctions_count`')
            ;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())

            ->ajaxWithForm(url()->current(), '#filter_form')
//            ->orderBy(1)
//            ->selectStyleSingle()
            ->buttons([
                Button::make('excel')->className('btn btn-dark')->text('<i class="ph-microsoft-excel-logo"></i> EXCEL'),
                Button::make('csv')->className('btn btn-info')->text('<i class="ph-file-csv"></i> CSV'),
                Button::make('print')->className('btn btn-success')->text('<i class="ph-printer"></i> طباعة'),
                Button::make('colvis')->className('btn btn-teal')->text('<i class="ph-list"></i>'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('profile_image')->title('صوره')->searchable(false)->orderable(false),
            Column::make('name')->title('الاسم'),
            Column::make('email')->title('البريد'),
            Column::make('mobile')->title('رقم الهاتف'),
            Column::make('type')->title('نوع الحساب')->searchable(false)->orderable(false)
                ->visible(!$this->type),

            Column::make('status')->title('حالة فتره التمييز')->searchable(false)->orderable(false)
                ->visible(!$this->status),

            Column::make('orders_count')->title('طلبات '),
            Column::make('transfers_count')->title('ايرادات '),
            Column::make('sanctions_count')->title('العقوبات'),


            Column::make('created_at')->title('تاريخ التسجيل'),
            Column::make('from_date')->title('تاريخ البدء'),
            Column::make('to_date')->title('تاريخ الانتهاء'),
            Column::computed('action')->title('الخيارات')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'UserDistinction_' . date('YmdHis');
    }
}
