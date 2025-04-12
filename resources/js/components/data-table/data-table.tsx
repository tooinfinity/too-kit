import Pagination from '@/components/data-table/pagination';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { router } from '@inertiajs/react';
import {
    ColumnDef,
    ColumnFiltersState,
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    PaginationState,
    SortingState,
    useReactTable,
} from '@tanstack/react-table';
import React from 'react';

interface PaginationProps {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

type DataTableProps<TData, TValue> = {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    meta?: PaginationProps;
    resourceName?: string;
    searchColumn?: string;
};

export default function DataTable<TData, TValue>({ data, columns, meta, resourceName, searchColumn }: DataTableProps<TData, TValue>) {
    const [sorting, setSorting] = React.useState<SortingState>([]);
    const [columnFilters, setColumnFilters] = React.useState<ColumnFiltersState>([]);
    const [pagination, setPagination] = React.useState<PaginationState>({
        pageIndex: meta ? meta.current_page - 1 : 0,
        pageSize: meta?.per_page || 10,
    });
    const [globalFilter, setGlobalFilter] = React.useState<string>('');

    const handlePageChange = (pageIndex: number) => {
        const page = pageIndex + 1;
        setPagination((prev) => ({
            ...prev,
            pageIndex,
        }));

        if (meta) {
            router.get(
                window.location.pathname,
                { page, search: globalFilter },
                {
                    preserveState: true,
                    preserveScroll: true,
                    only: [`${resourceName}`],
                },
            );
        }
    };

    const handleSearch = (value: string) => {
        setGlobalFilter(value);

        if (meta) {
            router.get(
                window.location.pathname,
                { page: 1, search: value },
                {
                    preserveState: true,
                    preserveScroll: true,
                    only: [`${resourceName}`],
                },
            );
        } else {
            // For client-side filtering
            table.getColumn(searchColumn as string)?.setFilterValue(value);
        }
    };

    const handleSortingChange = (updaterOrValue: SortingState | ((old: SortingState) => SortingState)) => {
        const newSorting = typeof updaterOrValue === 'function' ? updaterOrValue(sorting) : updaterOrValue;

        setSorting(newSorting);

        if (meta && newSorting.length > 0) {
            const { id, desc } = newSorting[0];
            const sortParam = desc ? `-${id}` : id;

            router.get(
                window.location.pathname,
                {
                    page: pagination.pageIndex + 1,
                    per_page: pagination.pageSize,
                    search: globalFilter,
                    sort: sortParam,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    only: [`${resourceName}`],
                },
            );
        }
    };

    const handlePerPageChange = (value: number) => {
        setPagination((prev) => ({
            ...prev,
            pageSize: value,
        }));

        if (meta) {
            router.get(
                window.location.pathname,
                { page: 1, per_page: value, search: globalFilter },
                {
                    preserveState: true,
                    preserveScroll: true,
                    only: [`${resourceName}`],
                },
            );
        }
    };

    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
        getSortedRowModel: getSortedRowModel(),
        onColumnFiltersChange: setColumnFilters,
        onSortingChange: handleSortingChange,
        onPaginationChange: setPagination,
        manualPagination: !!meta,
        pageCount: meta?.last_page || Math.ceil(data.length / pagination.pageSize),
        state: {
            columnFilters,
            sorting,
            pagination,
        },
    });
    return (
        <>
            <div className="flex items-center py-4">
                <Input className="max-w-sm" placeholder="Search..." value={globalFilter} onChange={(e) => handleSearch(e.target.value)} />
            </div>
            <div className={'rounded border p-5'}>
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => (
                                    <TableHead key={header.id}>
                                        {header.isPlaceholder ? null : flexRender(header.column.columnDef.header, header.getContext())}
                                    </TableHead>
                                ))}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row) => (
                                <TableRow key={row.id} data-state={row.getIsSelected() && 'selected'}>
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id}>{flexRender(cell.column.columnDef.cell, cell.getContext())}</TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell colSpan={columns.length} className={'h-24 text-center'}>
                                    No results found.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
            <Pagination table={table} totalRows={meta?.total} onPageChange={handlePageChange} onPerPageChange={handlePerPageChange} />
        </>
    );
}
