import React from 'react'
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
import { Box, Button, Grid, LinearProgress, Typography } from '@mui/material'
import Loader from '../Loader'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../../context/authContext'
import NewTeam from '../Modals/NewTeam'

interface DataRowInterface {
  id: number
  name: string
  isocode: string
  funds: number
  slug: string
}
interface DataTableDataInterface {
  teams: Array<DataRowInterface>
  count: number
}
const TeamDataTable = () => {
  const [page, setPage] = useState(0)
  const [rowsPerPage, setRowsPerPage] = useState(10)
  const [isFetchingData, setIsFetchingData] = useState(false)
  const { dispatch, token, payloads } = useAuth()
  const [data, setData] = useState<DataTableDataInterface>({
    teams: [],
    count: 0,
  })

  const navigate = useNavigate()
  const handleChangePage = (event: unknown, newPage: number) => {
    setPage(newPage)
  }

  const handleChangeRowsPerPage = (
    event: React.ChangeEvent<HTMLInputElement>
  ) => {
    setRowsPerPage(+event.target.value)
    setPage(0)
  }

  useEffect(() => {
    dispatch({ token: localStorage.getItem('app_token') })
  }, [])

  useEffect(() => {
    const _page = page + 1
    const url = `${process.env.API_URL}/teams/?page=${_page}&limit=${rowsPerPage}`
    setIsFetchingData(true)
    doQuery(url).then(({ data }) => {
      setData(data)
      setIsFetchingData(false)
    })
  }, [rowsPerPage, page])

  return (
    <Grid>
      <Typography
        component={'h3'}
        fontWeight={'bold'}
        textAlign={'center'}
        fontSize={'3rem'}
        margin={'auto'}
        mt={'150px'}
      >
        Team listing
      </Typography>

      <div
        style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
        }}
      >
        {isFetchingData ? (
          <Loader />
        ) : (
          <Paper sx={{ width: '100%', margin: '50px' }}>
            <TableContainer>
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell>Team</TableCell>
                    <TableCell>Country</TableCell>
                    <TableCell>Funds</TableCell>
                    <TableCell>Action</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {data.teams.length > 0 ? (
                    data.teams.map((team) => (
                      <TableRow hover role="checkbox" key={team.id}>
                        <TableCell>{team.name}</TableCell>
                        <TableCell>
                          {' '}
                          <img
                            loading="lazy"
                            width="20"
                            src={`https://flagcdn.com/w20/${team.isocode.toLowerCase()}.png`}
                            srcSet={`https://flagcdn.com/w40/${team.isocode.toLowerCase()}.png 2x`}
                            alt=""
                          />{' '}
                          {team.isocode}
                        </TableCell>
                        <TableCell>
                          ${team.funds.toLocaleString('en-US')}
                        </TableCell>
                        <TableCell>
                          <Button
                            variant="contained"
                            size={'small'}
                            onClick={(e) => navigate(`/team/${team.slug}`)}
                          >
                            View players
                          </Button>
                        </TableCell>
                      </TableRow>
                    ))
                  ) : (
                    <TableRow>
                      <TableCell colSpan={4} sx={{ textAlign: 'center' }}>
                        <Typography
                          component={'span'}
                          fontStyle={'italic'}
                          color={'#808080'}
                        >
                          No team available
                        </Typography>
                      </TableCell>
                    </TableRow>
                  )}
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
        )}
      </div>
      {token ? <NewTeam isOpen={!payloads?.team?.id} /> : ''}
    </Grid>
  )
}

export default TeamDataTable
