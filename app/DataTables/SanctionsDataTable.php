<?php

namespace App\DataTables;

use App\Models\Sanction;
use App\Support\AdminLabels;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SanctionsDataTable extends DataTable
{
    protected $user_id;

    public function __construct($user_id = null)
    {
        $this->user_id = $user_id;
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
            ->editColumn('photo', '<img style=" width: 50px; height: 50px; " src="{{asset($photo ?? "assets/images/demo/users/face11.jpg" )}}" />')
            ->editColumn('type', fn ($sanction) => " <span class='badge bg-success bg-opacity-10 text-success'>"
                . AdminLabels::sanctionType($sanction->type) . '</span>')
            ->editColumn('seen', fn ($sanction) => " <span class='badge bg-" . ($sanction->seen === 'seen' ? 'success' : 'warning') . " bg-opacity-10'>"
                . AdminLabels::sanctionSeen($sanction->seen) . '</span>')
            ->editColumn('created_at', function ($user) {
                return $user->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($user) {
                return $user->updated_at->toDateString();
            })
            ->editColumn('action', function ($sanction) {
                $editLink = route('sanctions.edit', $sanction);
                $showLink = route('sanctions.show', $sanction);
                $editLabel = __('messages.admin_edit');
                $showLabel = __('messages.admin_show');

                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="$editLink" class="dropdown-item"> <i class="ph-pencil me-2"></i> $editLabel </a>
                            <a href="$showLink" class="dropdown-item"> <i class="ph-eye me-2"></i> $showLabel </a>
                        </div>
                    </div>
                </div>
                HTML;
            })
            ->setRowId('id')
            ->rawColumns([
                'action',
                'photo',
                'type',
                'seen',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Sanction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Sanction $model): QueryBuilder
    {
        return $model->newQuery()
            ->with([
                'user' => function($q){
                    $q->select('id','name','mobile','gender');
                },
                'admin' => function($q1){
                    $q1->select('id','name');
                }
            ])
            ->when($this->user_id, function ($q) {
                $q->where('user_id', $this->user_id);
            })
            ->when($this->request->admin_id , function ($q){
                $q->where('admin_id' , $this->request->admin_id);
            })
            ->when($this->request->type , function ($q){
                $q->where('type' , $this->request->type);
            })
            ->when($this->request->seen , function ($q){
                $q->where('seen' , $this->request->seen);
            })
            ->whereHas('user', function ($q){
                $q->when($this->request->filled('country_id') || $this->request->filled('city_id'), function ($q) {
                    $r = [];
                    if ($this->request->filled('country_id')) $r['country_id'] = $this->request->country_id;
                    if ($this->request->filled('city_id')) $r['city_id'] = $this->request->city_id;
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
                ->orderBy(8)
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
            Column::make('photo')->title('photo'),
            Column::make('admin.name')->title('Admin'),
            Column::make('user.name')->title('User'),
            Column::make('type')->title('Type'),
            Column::make('seen')->title('Seen'),
            Column::make('note')->title('Note'),
            Column::make('start_time')->title('Start Time'),
            Column::make('end_time')->title('End Time'),
            Column::make('created_at'),
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
        return 'Sanctions_' . date('YmdHis');
    }
}
