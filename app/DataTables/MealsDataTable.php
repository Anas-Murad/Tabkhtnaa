<?php

namespace App\DataTables;

use App\Models\Meal;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MealsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('image', '<img style=" width: 50px; height: 50px; " src="{{asset($image ?? "assets/images/demo/users/face1.jpg" )}}" />')

            ->editColumn('created_at', function ($meal) {
                return  $meal->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($meal) {
                return  $meal->updated_at->toDateString();
            })
            ->editColumn('type' , function ($meal){
                return " <span class='badge bg-info text-white'> $meal->type</span>";
            })
            ->editColumn('is_active' , function ($meal){

                return $meal->is_active == 1 ? "<span class='badge bg-success text-white'> 'Active' </span>" : " <span class='badge bg-danger  text-white'> 'In Active' </span>";
            })
            ->editColumn('admin_note' , function ($meal){
                return $meal->admin_note ?? '-';
            })
            ->editColumn('preparation_time' , function ($meal){
                return $meal->preparation_time ?? '-';
            })
            ->editColumn('days' , function ($meal){
                return $meal->days ?? '-';
            })
            ->editColumn('action', function ($meal) {
                $EditLink = "EditMealFunction(" . htmlspecialchars(json_encode($meal), ENT_QUOTES, 'UTF-8') . ");";
                $ShowLink = (route('admin.meals.show' , $meal));
                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item" onclick="{$EditLink}"> <i class="ph-pencil me-2"></i> Edit </a>
                            <a href="$ShowLink" class="dropdown-item"> <i class="ph-eye me-2"></i> Show Information </a>
                        </div>
                    </div>
                </div>
                HTML;
            })
            ->setRowId('id')
            ->rawColumns([
                'action',
                'image',
                'type',
                'is_active',
                'admin_note',
                'preparation_time',
                'days',
                'created_at',
                'updated_at'
            ])
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Meal $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Meal $model): QueryBuilder
    {
        return $model->newQuery()
            ->with([
                'category' => function($q){
                     $q->select('id' , 'key');
                },
                'user' => function($q1){
                    $q1->select('id' , 'name');
                 }
            ])
            ->when($this->status , function ($q){
                $q->where('admin_status' , $this->status);
            })
            ->when($this->request->filled('name_meal'), function ($q) {
                $q->where('name','like', '%' . $this->request()->input('name_meal') . '%');
            })
            ->when($this->request->filled('code_meal'), function ($q) {
                $q->where('code','like', '%' . $this->request()->input('code_meal') . '%');
            })
            ->when($this->request->filled('description_meal'), function ($q) {
                $q->where('description','like', '%' . $this->request()->input('description_meal') . '%');
            })
            ->when($this->request->filled('price_meal'), function ($q) {
                $q->where('price', '<' , $this->request()->input('price_meal'));
            })
            ->when($this->request->filled('from_date'), function ($q) {
                $q->where('created_at', '>=', $this->request->input('from_date'));
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->where('created_at', '<=', $this->request->input('to_date'));
            })
            ->whereHas('user' , function ($q){
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
                    ->orderBy(13)
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
            Column::make('image')->title('Image'),
            Column::make('user.name')->title('Name Chef'),
            Column::make('category.key')->title('Category'),
            Column::make('name')->title('Name Meal'),
            Column::make('code')->title('Code'),
            Column::make('description')->title('Description'),
            Column::make('price')->title('Price'),
            Column::make('type')->title('Type'),
            Column::make('is_active')->title('Is Active'),
            Column::make('days')->title('Days'),
            Column::make('admin_status')->title('Admin Status'),
            Column::make('admin_note')->title('Admin Note'),
            Column::make('preparation_time')->title('Preparation Time'),
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
        return 'Meals_' . date('YmdHis');
    }
}
