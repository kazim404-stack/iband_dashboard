<?php

namespace App\DataTables;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SlidersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Slider> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.sliders.edit', $query->id) . '"  class="btn btn-primary btn-md edit-slider-btn" data-bs-toggle="modal" data-bs-target="#edit-slider" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.sliders.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#sliders-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('title', function ($query) {
                return $query->title ? $query->getTranslation('title', 'en') : '';
            })->addColumn('description', function ($query) {
                return $query->description ? $query->getTranslation('description', 'en') : '';
            })->addColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success p-1 text-white">True</span>' : '<span class="badge bg-warning p-1 text-white">False</span>';
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Slider>
     */
    public function query(Slider $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sliders-table')
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
            Column::make('title'),
            Column::make('description'),
            Column::make('status'),
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
        return 'Sliders_' . date('YmdHis');
    }
}
