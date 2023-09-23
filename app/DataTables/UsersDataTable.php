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
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->editColumn('profile_image','<img style=" width: 50px; height: 50px; " src="{{asset($profile_image ?? "assets/images/demo/users/face11.jpg" )}}" />')
            ->editColumn('email','<a href="mailto:{{$email}}">{{$email}}</a>')
            ->editColumn('mobile','<a href="tel:{{$country_code.$mobile}}">{{$country_code.$mobile}}</a>')

            ->editColumn('gender', function ($user) {
                return  $user->gender;
            })

            ->editColumn('type', function ($user) {
                return " <span class='badge bg-success bg-opacity-10 text-success'> $user->type</span>";
            })

            ->editColumn('account_status', function ($user) {
                return  $user->account_status;
            })

            ->editColumn('created_at', function ($user) {
                return  $user->created_at->toDateString();
            })

            ->editColumn('updated_at', function ($user) {
                return  $user->updated_at->toDateString();
            })

            ->editColumn('can_delivery', function ($user) {
                return  $user->can_delivery ? "Yes" :"No";
            })



            ->editColumn('action', function ($user) {
                return   <<<HTML

                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item"> <i class="ph-pencil me-2"></i> Edit </a>
                            <a href="#" class="dropdown-item"> <i class="ph-eye me-2"></i> Show Information </a>
                            <a href="#" class="dropdown-item"> <i class="ph-trash me-2"></i> Delete Account</a>
                        </div>
                    </div>
                </div>
                HTML;
            })

            ->setRowId('id')
            ->rawColumns(['action','email','mobile' ,'profile_image' ,'type'])

            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
//                    ->dom('<"datatable-header justify-content-start"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>')
//                    ->orderBy(1)
//                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
//                        Button::make('pdf'),
                        Button::make('print'),
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


            Column::make('id')->title('معر المستخدم'),
            Column::make('profile_image')->title('imag'),
            Column::make('name')->title('name'),
            Column::make('username')->title('username'),
            Column::make('email')->title('email'),
            Column::make('mobile')->title('mobile'),
            Column::make('dob')->title('dob'),
            Column::make('gender')->title('gender'),
            Column::make('type')->title('type'),
            Column::make('account_status')->title('status'),
            Column::make('can_delivery')->title('can delivery'),
            Column::make('created_at')->title('created at'),
            Column::computed('action')
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
