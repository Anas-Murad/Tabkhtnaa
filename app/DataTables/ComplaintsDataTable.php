<?php

namespace App\DataTables;

use App\Models\Complaint;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ComplaintsDataTable extends DataTable
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
            ->editColumn('photo','<img style=" width: 50px; height: 50px; " src="{{asset($photo ?? "assets/images/demo/users/face1.jpg" )}}" />')

            ->editColumn('order_id', function ($complaint) {
                return  "<a href='$complaint->order_id' target='_blank'> $complaint->order_id </a>";
            })

            ->editColumn('type', function ($complaint) {
                return " <span class='badge bg-info  text-white'> $complaint->type</span>";
            })

            ->editColumn('status', function ($complaint) {
              $status =   $complaint->status == null ? '-' :
                  ($complaint->status == 'solved' ? "<span class='badge bg-success text-white'> $complaint->status</span>" : " <span class='badge bg-danger  text-white'> $complaint->status</span>");
                return $status;
            })

            ->editColumn('note', function ($complaint) {
                return  $complaint->note ?? '-';
            })

            ->editColumn('created_at', function ($complaint) {
                return  $complaint->created_at->toDateString();
            })

            ->editColumn('updated_at', function ($complaint) {
                return  $complaint->updated_at->toDateString();
            })

            ->editColumn('action', function ($complaint) {
                $EditLink = "EditComplaintFunction(" . htmlspecialchars(json_encode($complaint), ENT_QUOTES, 'UTF-8') . ");";
                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item" onclick="{$EditLink}"> <i class="ph-pencil me-2"></i> Edit </a>
                        </div>
                    </div>
                </div>
                HTML;
            })

            ->setRowId('id')
            ->rawColumns(['action' ,'photo' , 'type' , 'status' , 'note' ,'order_id'])
            ;
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Complaint $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Complaint $model): QueryBuilder
    {
        return $model->newQuery()
            ->with([
            'user' => function($q){
                $q->select('id','name','mobile','gender');
            },
            'admin' => function($q1){
                $q1->select('id','name');
            }])
            ->whereHas('order', function ($q){
                $q->when($this->request->filled('order_id'), function ($q) {
                    $q->where('id', $this->request->order_id);
                });
            })

            ->whereHas('user', function ($q){
                $q->when($this->request->filled('country_id') || $this->request->filled('city_id'), function ($q) {
                    $r = [];
                    if ($this->request->filled('country_id')) $r['country_id'] = $this->request->country_id;
                    if ($this->request->filled('city_id')) $r['city_id'] = $this->request->country_id;
                    $q->whereRelation('userAddress', $r);
                })->when($this->request->filled('search_key'), function ($q) {
                    $q->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('email', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('country_code', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('mobile', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('username', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('account_comment', 'like', '%' . $this->request()->input('search_key') . '%');
                    });

                })->when($this->request->filled('gender'), function ($q) {
                        $q->where('gender', $this->request->gender);
                    });
            })

            ->when($this->request->admin_id , function ($q){
                $q->where('admin_id' , $this->request->admin_id);
            })
            ->when($this->request->filled('type'), function ($q) {
                $q->where('type', $this->request->type);
            })
            ->when($this->request->filled('from_date'), function ($q) {
                $q->where('created_at', '>=', $this->request->input('from_date'));
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->where('created_at', '<=', $this->request->input('to_date'));
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
                    ->minifiedAjax()
                    ->orderBy(7)
                    ->selectStyleSingle()
                    ->ajaxWithForm(url()->current(), '#filter_form')
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('print'),
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
            Column::make('photo')->title('Photo'),
            Column::make('user.name')->title('User'),
            Column::make('user.mobile')->title('Mobile'),
            Column::make('order_id')->title('Order'),
            Column::make('type')->title('Type'),
            Column::make('description')->title('Description'),
            Column::make('status')->title('Status'),
            Column::make('note')->title('Note'),
            Column::make('admin.name')->content('-')->title('Admin'),
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
        return 'Complaints_' . date('YmdHis');
    }
}
