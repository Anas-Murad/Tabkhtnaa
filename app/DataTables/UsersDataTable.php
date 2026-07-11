<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{

    protected $type;
    protected $status;

    public function __construct($type = null, $status = null)
    {
        $this->type = $type;
        $this->status = $status;
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
            ->editColumn('profile_image', '<img style=" width: 50px; height: 50px; " src="{{asset($profile_image ?? "assets/images/demo/users/face11.jpg" )}}" />')
            ->editColumn('email', '<a href="mailto:{{$email}}">{{$email}}</a>')
            ->editColumn('mobile', '<a href="tel:{{$country_code.$mobile}}">{{$country_code.$mobile}}</a>')
            ->editColumn('gender', function ($user) {
                return " <span class='badge bg-success bg-opacity-10 text-success'>" . __('messages.' . $user->gender) . "</span>";
            })
            ->editColumn('type', function ($user) {
                return " <span class='badge bg-success bg-opacity-10 text-success'>" . __('messages.' . $user->type) . "</span>";
            })
            ->editColumn('account_status', function ($user) {

                switch ($user->account_status) {
                    case 'pending' :
                        return " <span class='badge bg-warning bg-opacity-10 text-warning'>" . __('messages.' . $user->account_status) . "</span>";

                    case 'active' :
                        return " <span class='badge bg-success bg-opacity-10 text-success'>" . __('messages.' . $user->account_status) . "</span>";

                    case 'rejected' :
                        return " <span class='badge bg-danger bg-opacity-10 text-danger'>" . __('messages.' . $user->account_status) . "</span>";

                    case 'blocked' :
                        return " <span class='badge bg-dark   text-white'>" . __('messages.' . $user->account_status) . "</span>";

                }
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($user) {
                return $user->updated_at->toDateString();
            })
            ->editColumn('can_delivery', function ($user) {

                switch ($user->can_delivery) {
                    case 'no' :
                        return " <span class='badge bg-dark'>" . __('messages.' . $user->can_delivery) . "</span>";
                    case 'request' :
                        return " <span class='badge bg-warning '>" . __('messages.' . $user->can_delivery) . "</span>";

                    case 'yes' :
                        return " <span class='badge bg-success'>" . __('messages.' . $user->can_delivery) . "</span>";

                    case 'rejected' :
                        return " <span class='badge bg-danger   text-white'>" . __('messages.' . $user->can_delivery) . "</span>";

                }


            })

            ->editColumn('action', function ($user) {
                $deleteFunction = "DeleteFunction('" . route('users.destroy', $user) . "')";
                $editLink = route('users.edit', $user);
                $showLink = route('users.show', $user);
                $editLabel = __('messages.admin_edit');
                $showLabel = __('messages.admin_show');
                $deleteLabel = __('messages.admin_delete_account');

                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="$editLink" class="dropdown-item"> <i class="ph-pencil me-2"></i> $editLabel </a>
                            <a href="$showLink" class="dropdown-item"> <i class="ph-eye me-2"></i> $showLabel </a>
                            <a href="#" class="dropdown-item" onclick="{$deleteFunction}"> <i class="ph-trash me-2"></i> $deleteLabel</a>
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
                'type',
                'gender',
                'account_status',
                'can_delivery',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('residenceCountry')
            ->when($this->type, function ($q) {
                $q->where('type', $this->type);
            })
            ->when($this->status, function ($q) {
                $q->where('account_status', $this->status);
            })
            ->when($this->request->filled('country_id') || $this->request->filled('city_id'), function ($q) {
                $r = [];
                if ($this->request->filled('country_id')) $r['country_id'] = $this->request->country_id;
                if ($this->request->filled('city_id')) $r['city_id'] = $this->request->country_id;
                $q->whereRelation('userAddress', $r);
            })
            ->when($this->request->filled('from_date'), function ($q) {
                $q->where('created_at', '>=', $this->request()->input('from_date'));
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->where('created_at', '<=', $this->request()->input('to_date'));
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
            ->when($this->gender, function ($q) {
                $q->where('gender', $this->gender);
            })
            ->when($this->source, function ($q) {
                $q->where('source', $this->source);
            })
            ->when($this->online_status, function ($q) {
                $q->where('online_status', $this->online_status);
            })
            ->when($this->type, function ($q) {
                $q->where('type', $this->type);
            })
            ->when($this->can_delivery, function ($q) {
                $q->where('can_delivery', $this->can_delivery);
            })
            ->when($this->account_status, function ($q) {
                $q->where('account_status', $this->account_status);
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
//            ->minifiedAjax()
//                    ->dom('<"datatable-header justify-content-start"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>')
//                    ->orderBy(1)
//                    ->selectStyleSingle()
            ->buttons([
                Button::make('excel')->className('btn btn-dark')->text('<i class="ph-microsoft-excel-logo"></i> EXCEL'),
                Button::make('csv')->className('btn btn-info')->text('<i class="ph-file-csv"></i> CSV'),
                Button::make('print')->className('btn btn-success')->text('<i class="ph-printer"></i> طباعة'),
                Button::make('colvis')->className('btn btn-teal')->text('<i class="ph-list"></i>'),
//                Button::make('colvis')->action("myCustomAction")
//                    ->className('btn btn-teal')->text('<i class="ph-plus"></i> اضافه'),
//                        Button::make('reset'),
//                        Button::make('reload')

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
            Column::make('email')->title('البريد'),
            Column::make('mobile')->title('رقم الهاتف'),
            Column::make('residence_country.name')->title('بلد الاقامة'),
            Column::make('dob')->title('تاريخ الميلاد'),
            Column::make('gender')->title('الجنس')->searchable(false)->orderable(false),

            Column::make('type')->title('نوع الحساب')->searchable(false)->orderable(false)
                ->visible(!$this->type),
            Column::make('account_status')->title('حالة الحساب')
                ->visible(!$this->status),


            Column::make('can_delivery')->title('امكانيه التوصيل'),
            Column::make('created_at')->title('تاريخ التسجيل'),
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
        return 'Users_' . date('YmdHis');
    }

}
