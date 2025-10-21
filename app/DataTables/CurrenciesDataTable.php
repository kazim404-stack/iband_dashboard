<?php

namespace App\DataTables;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CurrenciesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Currency> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.currencies.edit', $query->id) . '"  class="btn btn-primary btn-md edit-currency-btn" data-bs-toggle="modal" data-bs-target="#edit-currency" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.currencies.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#currencies-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('is_default',function($query){
                return $query->is_default == 1 ? '<span class="badge bg-success text-white">Yes</span>' : '<span class="badge bg-primary text-white">No</span>';
            })
            ->rawColumns(['action','is_default'])
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Currency>
     */
    public function query(Currency $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('currencies-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('code'),
            Column::make('symbol'),
            Column::make('exchange_rate'),
            Column::make('is_default'),
             Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Currencies_' . date('YmdHis');
    }
}
