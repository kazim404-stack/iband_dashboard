<?php

namespace App\DataTables;

use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AttributeValuesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<AttributeValue> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.attribute-values.edit', $query->id) . '"  class="btn btn-primary btn-md edit-attribute-value-btn" data-bs-toggle="modal" data-bs-target="#edit-attribute-value" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.attribute-values.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#attributevalues-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('value', function ($query) {
                return $query->value ? $query->getTranslation('value', 'en') : '';
            })->addColumn('attribute_name', function ($query) {
                return $query->attribute ? $query->attribute->getTranslation('name', 'en') : '';
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<AttributeValue>
     */
    public function query(AttributeValue $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('attributevalues-table')
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
            Column::make('attribute_name'),
            Column::make('value'),
            Column::make('slug'),
            Column::make('sort_order'),
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
        return 'AttributeValues_' . date('YmdHis');
    }
}
