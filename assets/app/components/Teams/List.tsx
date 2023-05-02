import React, { useEffect, useMemo, useState } from 'react'

import MaterialReactTable, {
  MRT_ColumnDef,
  MRT_ColumnFiltersState,
  MRT_PaginationState,
  MRT_SortingState,
} from 'material-react-table'
import { columns, Player } from './PlayerDatatableAttributes'

type UserApiResponse = {
  data: Array<Player>
  meta: {
    totalRowCount: number
  }
}

const TeamList = () => {
  const [data, setData] = useState<Player[]>([])
  const [isError, setIsError] = useState<boolean>(false)
  const [isLoading, setIsLoading] = useState<boolean>(false)
  const [isRefetching, setIsRefetching] = useState<boolean>(false)
  const [rowCount, setRowCount] = useState(0)

  //table state
  const [columnFilters, setColumnFilters] = useState<MRT_ColumnFiltersState>([])
  const [globalFilter, setGlobalFilter] = useState('')
  const [sorting, setSorting] = useState<MRT_SortingState>([])
  const [pagination, setPagination] = useState<MRT_PaginationState>({
    pageIndex: 0,
    pageSize: 10,
  })

  //if you want to avoid useEffect, look at the React Query example instead
  useEffect(() => {
    const fetchData = async () => {
      if (!data.length) {
        setIsLoading(true)
      } else {
        setIsRefetching(true)
      }

      const url = new URL('/api/players', process.env.API_URL)
      url.searchParams.set(
        'start',
        `${pagination.pageIndex * pagination.pageSize}`
      )
      url.searchParams.set('size', `${pagination.pageSize}`)
      url.searchParams.set('filters', JSON.stringify(columnFilters ?? []))
      url.searchParams.set('globalFilter', globalFilter ?? '')
      url.searchParams.set('sorting', JSON.stringify(sorting ?? []))

      try {
        const response = await fetch(url.href)
        const json = (await response.json()) as UserApiResponse
        setData(json.data)
        setRowCount(json.meta.totalRowCount)
      } catch (error) {
        setIsError(true)
        console.error(error)
        return
      }
      setIsError(false)
      setIsLoading(false)
      setIsRefetching(false)
    }
    fetchData()
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [
    columnFilters,
    globalFilter,
    pagination.pageIndex,
    pagination.pageSize,
    sorting,
  ])

  return (
    <div
      style={{
        margin: '50px',
        justifyContent: 'center',
        alignItems: 'center',
        height: '100vh',
      }}
    >
      <MaterialReactTable
        columns={columns}
        data={data}
        enableRowSelection
        getRowId={(row) => row.team}
        initialState={{ showColumnFilters: true }}
        manualFiltering
        manualPagination
        manualSorting
        muiToolbarAlertBannerProps={
          isError
            ? {
                color: 'error',
                children: 'Error loading data',
              }
            : undefined
        }
        onColumnFiltersChange={setColumnFilters}
        onGlobalFilterChange={setGlobalFilter}
        onPaginationChange={setPagination}
        onSortingChange={setSorting}
        rowCount={rowCount}
        state={{
          columnFilters,
          globalFilter,
          isLoading,
          pagination,
          showAlertBanner: isError,
          showProgressBars: isRefetching,
          sorting,
        }}
      />
    </div>
  )
}

export default TeamList
