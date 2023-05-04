import { Routes, Route, useParams } from 'react-router-dom'
import React, { useEffect, useState } from 'react'
import { Button, Grid, InputLabel, Typography } from '@mui/material'
import Loader from '../Loader'
import Paper from '@mui/material/Paper'
import TableContainer from '@mui/material/TableContainer'
import Table from '@mui/material/Table'
import TableHead from '@mui/material/TableHead'
import TableRow from '@mui/material/TableRow'
import TableCell from '@mui/material/TableCell'
import TableBody from '@mui/material/TableBody'
import TablePagination from '@mui/material/TablePagination'
import { doQuery, getRandomInt } from '../../utils'
import { AuthContextInterface, useAuth } from '../../context/authContext'
type PlayerType = {
  id: number
  name: string
  surname: string
}
const Players = () => {
  const { id: teamId } = useParams()
  const [isFetchingData, setIsFetchingData] = useState(false)
  const [data, setData] = useState<PlayerType[]>([])
  const { dispatch, payloads } = useAuth()
  const hasTeam: boolean = payloads?.team?.id != null

  useEffect(() => {
    dispatch({ token: localStorage.getItem('app_token') })
    const url = `${process.env.API_URL}/teams/${teamId}/players`
    setIsFetchingData(true)
    doQuery(url).then(({ data }) => {
      setData(data)
      setIsFetchingData(false)
    })
  }, [])

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
        Players of team
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
        ) : data.length > 0 ? (
          <Paper sx={{ width: '100%', margin: '50px' }}>
            <TableContainer>
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell>Name</TableCell>
                    <TableCell>Surname</TableCell>
                    <TableCell>Action</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {data.map((_data) => (
                    <TableRow key={_data.id}>
                      <TableCell>{_data.name}</TableCell>
                      <TableCell>{_data.surname}</TableCell>
                      <TableCell>
                        <Button
                          variant="contained"
                          size={'small'}
                          disabled={!hasTeam}
                        >
                          Buy player
                        </Button>
                        {hasTeam ? (
                          <Typography
                            ml={2}
                            component={'span'}
                            color={'#fff'}
                            sx={{
                              fontWeight: 'bold',
                              backgroundColor: '#378d53',
                              padding: '2px',
                              borderRadius: '2px',
                            }}
                          >
                            $
                            {getRandomInt(100000, 500000).toLocaleString(
                              'en-US'
                            )}
                          </Typography>
                        ) : (
                          ''
                        )}
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </TableContainer>
          </Paper>
        ) : (
          <Typography>No player available</Typography>
        )}
      </div>
    </Grid>
  )
}

export default Players
