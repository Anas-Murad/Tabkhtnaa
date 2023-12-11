<?php

namespace App\DataTables;

use App\Models\Country;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CountryDataTable extends DataTable
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
            ->editColumn('flag', function ($row) {
                return $row->flag? "نشط" : "غير نشط";
            })

            ->editColumn('action', function ($Transaction) {

                $btns =" <a href='".(route('admin.settings.countries.cities' , $Transaction->id))."' class='dropdown-item'><i class='ph-list me-2'></i>  عرض المدن</a>";
                $btns .=" <a href='".(route('admin.settings.cities.create' , $Transaction->id))."' class='dropdown-item'><i class='ph-plus-circle me-2'></i>   اضف مدينه</a>";
                $btns .=" <a href='".(route('admin.settings.countries.edit' , $Transaction->id))."' class='dropdown-item'><i class='ph-paint-brush-broad me-2'></i>    تعديل</a>";
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


            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Country $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Country $model): QueryBuilder
    {
        return $model->newQuery()
            ->withCount('cities');
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

            Column::make('native')->title('اسم الدولة'),
            Column::make('name')->title('name'),
            Column::make('iso3')->title('iso3'),
            Column::make('iso3')->title('iso2'),
            Column::make('phonecode')->title('مفتاح الدولة'),
            Column::make('capital')->title('العاصمة'),
            Column::make('currency')->title('العملة'),
            Column::make('region')->title('القارة'),
            Column::make('latitude')->title('latitude'),
            Column::make('longitude')->title('longitude'),
            Column::make('flag')->title('حاله الدولة'),
            Column::make('cities_count')->title('عدد المدن')
            ->searchable(false)
            ->orderable(false)
            ,

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
        return 'Country_' . date('YmdHis');
    }
}
