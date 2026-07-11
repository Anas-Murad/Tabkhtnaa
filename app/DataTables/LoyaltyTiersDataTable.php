<?php

namespace App\DataTables;

use App\Models\LoyaltyTier;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoyaltyTiersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('is_active', fn ($tier) => $tier->is_active
                ? "<span class='badge bg-success'>نشط</span>"
                : "<span class='badge bg-secondary'>معطل</span>")
            ->editColumn('action', function ($tier) {
                $deleteRoute = route('admin.loyalty.tiers.destroy', $tier->id);
                $edit = 'EditTier(' . htmlspecialchars(json_encode($tier), ENT_QUOTES, 'UTF-8') . ');';

                return <<<HTML
                <div class="d-inline-flex">
                    <div class="dropdown">
                        <a href="#" class="text-body" data-bs-toggle="dropdown"><i class="ph-list"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item" onclick="{$edit}"><i class="ph-pencil me-2"></i> تعديل</a>
                            <a href="#" class="dropdown-item text-danger" onclick="DeleteFunction('{$deleteRoute}');"><i class="ph-trash me-2"></i> حذف</a>
                        </div>
                    </div>
                </div>
                HTML;
            })
            ->rawColumns(['is_active', 'action'])
            ->setRowId('id');
    }

    public function query(LoyaltyTier $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('level');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([
                Button::make()->action('createTier()')->className('btn btn-teal')->text('<i class="ph-plus"></i> إضافة مستوى'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('الاسم'),
            Column::make('level')->title('المستوى'),
            Column::make('min_lifetime_spending')->title('الإنفاق المطلوب'),
            Column::make('points_multiplier')->title('مضاعف النقاط'),
            Column::make('min_redemption_points')->title('حد الاستبدال'),
            Column::make('is_active')->title('الحالة'),
            Column::computed('action')->exportable(false)->printable(false)->width(60),
        ];
    }

    protected function filename(): string
    {
        return 'LoyaltyTiers_' . date('YmdHis');
    }
}
