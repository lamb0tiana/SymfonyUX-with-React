import * as React from 'react'
import Paper from '@mui/material/Paper'
import Table from '@mui/material/Table'
import TableBody from '@mui/material/TableBody'
import TableCell from '@mui/material/TableCell'
import TableContainer from '@mui/material/TableContainer'
import TableHead from '@mui/material/TableHead'
import TablePagination from '@mui/material/TablePagination'
import TableRow from '@mui/material/TableRow'
import { doQuery } from '../../utils'
import { useEffect, useState } from 'react'
import { Grid, Typography } from '@mui/material'

interface DataRowInterface {
  id: number
  name: string
  isocode: string
  funds: number
}
interface DataTableDataInterface {
  teams: Array<DataRowInterface>
  count: number
}
const TeamDataTable = () => {
  const [page, setPage] = React.useState(0)
  const [rowsPerPage, setRowsPerPage] = React.useState(10)

  const handleChangePage = (event: unknown, newPage: number) => {
    setPage(newPage)
  }

  const handleChangeRowsPerPage = (
    event: React.ChangeEvent<HTMLInputElement>
  ) => {
    setRowsPerPage(+event.target.value)
    setPage(0)
  }

  const [data, setData] = useState<DataTableDataInterface>({
    teams: [],
    count: 0,
  })
  useEffect(() => {
    doQuery(`${process.env.API_URL}/teams/`).then(({ data }) => setData(data))
  }, [])

  return (
    <Grid container>
      <Typography
        component={'h3'}
        fontWeight={'bold'}
        textAlign={'center'}
        margin={'auto'}
      >
        Teams listing
      </Typography>
      <Paper sx={{ width: '100%', margin: '50px' }}>
        <TableContainer>
          <Table>
            <TableHead>
              <TableRow>
                <TableCell>Team</TableCell>
                <TableCell>Country</TableCell>
                <TableCell>Funds</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {data.teams.map((team) => (
                <TableRow>
                  <TableCell>{team.name}</TableCell>
                  <TableCell>{team.isocode}</TableCell>
                  <TableCell>{team.funds}</TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
        <TablePagination
          rowsPerPageOptions={[10, 25, 100]}
          component="div"
          count={data.count}
          rowsPerPage={rowsPerPage}
          page={page}
          onPageChange={handleChangePage}
          onRowsPerPageChange={handleChangeRowsPerPage}
        />
      </Paper>
    </Grid>
  )
}

export default TeamDataTable
