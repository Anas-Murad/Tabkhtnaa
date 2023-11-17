<?php

namespace App\DataTables;

use App\Models\DriverCash;
use App\Models\User;
use App\Models\UserDriverCash;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DriverCashDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('profile_image', '<img style=" width: 50px; height: 50px; " src="{{asset($profile_image ?? "assets/images/demo/users/face11.jpg" )}}" />')
            ->editColumn('email', '<a href="mailto:{{$email}}">{{$email}}</a>')
            ->editColumn('mobile', '<a href="tel:{{$country_code.$mobile}}">{{$country_code.$mobile}}</a>')

            ->editColumn('action', function ($user) {

                $btns =" <a href='".(route('admin.driver-cash-user' , $user))."' class='dropdown-item'> <i class='ph-list-bullets me-2'></i>تفاصيل  حركات الكاش المفتوح</a>";
                $btns .=" <a href='".(route('admin.transfer.records_user' , $user))."' class='dropdown-item'> <i class='ph-list-bullets me-2'></i>  تفاصيل الحركات</a>";
                $btns .=" <a href='".(route('users.show' , $user))."' class='dropdown-item'> <i class='ph-eye me-2'></i> ملف العميل</a>";
                $btns .=" <a href='".(route('users.show' , $user))."' class='dropdown-item'> <i class='ph-money me-2'></i> الايرادات</a>";


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
            ->setRowId('id')
            ->rawColumns([
                'action',
                'email',
                'mobile',
                'profile_image',

            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DriverCash $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('residenceCountry')


            ->whereHas('userDriverCashes' , function ($q){return $q->where(['status'=>'pending'])
                ->when($this->request->filled('from_date'), function ($q) {
                    $q->where('created_at', '>=', $this->request()->input('from_date'));
                })
                ->when($this->request->filled('to_date'), function ($q) {
                    $q->where('created_at', '<=', $this->request()->input('to_date'));
                })
                ; })

            ->withSum( [
                'userDriverCashes as total_cash'=> function ($q){return $q->where([ 'status'=>'pending'])
                    ->when($this->request->filled('from_date'), function ($q) {
                        $q->where('created_at', '>=', $this->request()->input('from_date'));
                    })
                    ->when($this->request->filled('to_date'), function ($q) {
                        $q->where('created_at', '<=', $this->request()->input('to_date'));
                    })
                    ; }
            ] ,'total_cash')






            ->when($this->request->filled('country_id') || $this->request->filled('city_id'), function ($q) {
                $r = [];
                if ($this->request->filled('country_id')) $r['country_id'] = $this->request->country_id;
                if ($this->request->filled('city_id')) $r['city_id'] = $this->request->country_id;
                $q->whereRelation('userAddress', $r);
            })





            ->when($this->request->filled('search_key'), function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->request()->input('search_key') . '%');
                    $q->orWhere('email', 'like', '%' . $this->request()->input('search_key') . '%');
                    $q->orWhere('country_code', 'like', '%' . $this->request()->input('search_key') . '%');
                    $q->orWhere('mobile', 'like', '%' . $this->request()->input('search_key') . '%');
                    $q->orWhere('username', 'like', '%' . $this->request()->input('search_key') . '%');
                    $q->orWhere('account_comment', 'like', '%' . $this->request()->input('search_key') . '%');
                });

            })





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
            ->orderBy(0)
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

            Column::make('id')->title('#ID'),
            Column::make('profile_image')->title('صوره'),
            Column::make('name')->title('الاسم'),
            Column::make('username')->title('اسم المتسخدم'),
            Column::make('mobile')->title('رقم الهاتف'),
            Column::make('residence_country.name')->title('بلد الاقامة')->searchable(false)->orderable(false),
            Column::make('total_cash')->title('اجمالي الكاش'),
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
        return 'DriverCash_' . date('YmdHis');
    }
}
