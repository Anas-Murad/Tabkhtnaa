<?php

namespace App\DataTables;

use App\Models\DoublePointsCampaign;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DoublePointsCampaignsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('is_active', fn ($c) => $c->is_active
                ? "<span class='badge bg-success'>نشطة</span>"
                : "<span class='badge bg-secondary'>معطلة</span>")
            ->addColumn('period', fn ($c) => $c->start_date->format('Y-m-d') . ' → ' . $c->end_date->format('Y-m-d'))
            ->editColumn('action', function ($c) {
                $editRoute = route('admin.loyalty.campaigns.update', $c->id);
                $deleteRoute = route('admin.loyalty.campaigns.destroy', $c->id);

                return <<<HTML
                <a href="#" class="text-primary me-2" onclick="editCampaign({$c->id}, '{$c->name}', '{$c->start_date->format('Y-m-d')}', '{$c->end_date->format('Y-m-d')}', '{$c->multiplier}', '{$c->applies_to}', {$c->is_active}); return false;"><i class="ph-pencil"></i></a>
                <a href="#" class="text-danger" onclick="DeleteFunction('{$deleteRoute}');"><i class="ph-trash"></i></a>
                HTML;
            })
            ->rawColumns(['is_active', 'action'])
            ->setRowId('id');
    }

    public function query(DoublePointsCampaign $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->buttons([
                Button::make()->action('createCampaign()')->className('btn btn-teal')->text('<i class="ph-plus"></i> حملة جديدة'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('الاسم'),
            Column::computed('period')->title('الفترة'),
            Column::make('multiplier')->title('المضاعف'),
            Column::make('applies_to')->title('ينطبق على'),
            Column::make('is_active')->title('الحالة'),
            Column::computed('action')->exportable(false)->printable(false)->width(40),
        ];
    }

    protected function filename(): string
    {
        return 'DoublePointsCampaigns_' . date('YmdHis');
    }
}
