<?php

namespace App\DataTables;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ContactsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Contact> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.contacts.edit', $query->id) . '"  class="btn btn-primary btn-md edit-contact-btn" data-bs-toggle="modal" data-bs-target="#edit-contact" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.contacts.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#contacts-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('site_name', function ($query) {
                return $query->generalSetting ? $query->generalSetting->site_name : '';
            })->addColumn('state', function ($query) {
                return $query->state ? $query->getTranslation('state', 'en') : '';
            })->addColumn('address', function ($query) {
                return $query->address ? $query->getTranslation('address', 'en') : '';
            })->addColumn('is_primary', function ($query) {
                return $query->is_primary == 1 ? '<span class="badge bg-success p-1 text-white">True</span>' : '<span class="badge bg-warning p-1 text-white">False</span>';
            })
            ->rawColumns(['action','is_primary'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Contact>
     */
    public function query(Contact $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('contacts-table')
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
            Column::make('site_name'),
            Column::make('state'),
            Column::make('email'),
            Column::make('address'),
            Column::make('is_primary'),
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
        return 'Contacts_' . date('YmdHis');
    }
}
