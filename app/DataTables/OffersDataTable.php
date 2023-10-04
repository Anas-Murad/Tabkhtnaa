<?php

namespace App\DataTables;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OffersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */

    protected $type;
    public function __construct($type = null)
    {
        $this->type = $type;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('image' , '<img style=" width: 50px; height: 50px; " src="{{asset($image ?? "assets/images/demo/users/face1.jpg" )}}" />')
            ->editColumn('number' , function ($offer){
                return $offer->number ?? '-';
            })
            ->editColumn('percent' , function ($offer){
                return '%'.$offer->percent;
            })
            ->editColumn('meal_id', function ($offer) {
                $mealLink = (route('admin.meals.show' , $offer->meal->id));
                return  "<a href='$mealLink' target='_blank'> {$offer->meal->name} </a>";
            })
            ->setRowId('id')
            ->rawColumns(['image' , 'number' , 'meal_id'])
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Offer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Offer $model): QueryBuilder
    {
        return $model->newQuery()
            ->with([
                'meal' => function($q){
                    $q->select('id' , 'name');
                }
            ])
            ->when($this->type , function ($q){
                $q->where('type' , $this->type);
            })
            ->when($this->request->filled('number'),function ($q){
                $q->where('number' , $this->request->number);
            })
            ->when($this->request->filled('percent'),function ($q){
                $q->where('percent' , '<=' , $this->request->percent);
            })
            ->when($this->request->filled('start_date'), function ($q) {
                $q->where('start_date', '>=', $this->request()->input('start_date'));
            })
            ->when($this->request->filled('end_date'), function ($q) {
                $q->where('end_date', '<=', $this->request()->input('end_date'));
            })
            ->when($this->request->filled('from_date'), function ($q) {
                $q->where('created_at', '>=', $this->request->input('from_date'));
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->where('created_at', '<=', $this->request->input('to_date'));
            })
            ->whereHas('meal' , function ($q){
                $q->when($this->request->filled('search_key'), function ($q) {
                    $q->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('code', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('price', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('description', 'like', '%' . $this->request()->input('search_key') . '%');
                    });
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
                        Button::make('pdf'),
                        Button::make('print')
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
            Column::make('meal_id')->title('Meal'),
            Column::make('number')->title('Number'),
            Column::make('percent')->title('Percent'),
            Column::make('get_free')->title('Get Free'),
            Column::make('type')->title('Type'),
            Column::make('start_date')->title('Start Date'),
            Column::make('end_date')->title('End Date'),
            Column::make('created_at'),
//            Column::computed('action')
//                  ->exportable(false)
//                  ->printable(false)
//                  ->width(60)
//                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Offers_' . date('YmdHis');
    }
}
