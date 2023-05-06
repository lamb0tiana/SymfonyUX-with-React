import { Routes, Route, useParams } from 'react-router-dom'
import React, { useEffect, useState, useRef, createRef, RefObject } from 'react'
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
import PlayerWorth, {
  DefinitionWorthInterface,
  RefWorthModalRefInterface,
} from '../Modals/PlayerWorth'
import NewTeam from '../Modals/NewTeam'
import NewPlayer, { RefNewPlayerInterface } from '../Modals/NewPlayer'
type PlayerType = {
  id: number
  name: string
  surname: string
  worth: number
}
const Players = () => {
  const { slug } = useParams()
  const [isFetchingData, setIsFetchingData] = useState(false)
  const [data, setData] = useState<PlayerType[]>([])
  const { dispatch, payloads, token } = useAuth()
  const [isAddPlayer, setIsAddPlayer] = useState<boolean>(false)
  const hasTeam: boolean = payloads?.team?.id != null
  const [isOwner, setIsOwner] = useState<boolean>(false)
  useEffect(() => {
    dispatch({ token: localStorage.getItem('app_token') })
    const url = `${process.env.API_URL}/teams/${slug}/players`
    setIsFetchingData(true)
    doQuery(url).then(({ data }) => {
      setData(data)
      setIsFetchingData(false)
    })
  }, [])

  useEffect(() => {
    setIsOwner(slug === payloads?.team?.slug)
  }, [payloads])

  const PlayerWorthRef: React.RefObject<RefWorthModalRefInterface> =
    useRef(null)

  const newPlayerRef: React.RefObject<RefNewPlayerInterface> = useRef(null)
  return (
    <Grid textAlign={'center'}>
      <Typography
        component={'h3'}
        fontWeight={'bold'}
        fontSize={'3rem'}
        mt={'150px'}
      >
        Players of team
      </Typography>
      <Button
        size={'small'}
        variant="contained"
        color="primary"
        style={{ marginTop: '1rem' }}
        onClick={() => newPlayerRef.current.openModal()}
      >
        Add player
      </Button>
      <div
        style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          marginTop: '15px',
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
                  {data.map(({ id, name, surname, worth }) => (
                    <TableRow key={id}>
                      <TableCell>{name}</TableCell>
                      <TableCell>{surname}</TableCell>
                      <TableCell>
                        <Button
                          color={isOwner ? 'success' : 'primary'}
                          variant="contained"
                          size={'small'}
                          disabled={!hasTeam}
                          onClick={() =>
                            PlayerWorthRef.current.handleOpen({ id, worth })
                          }
                        >
                          {`${
                            isOwner
                              ? worth
                                ? 'Edit worth'
                                : 'Sell player'
                              : 'Buy player'
                          }`}
                        </Button>
                        {hasTeam && worth ? (
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
                            ${worth.toLocaleString('en-US')}
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
      <PlayerWorth ref={PlayerWorthRef} />
      {token ? <NewTeam isOpen={!payloads?.team?.id} /> : ''}
      <NewPlayer ref={newPlayerRef} />
    </Grid>
  )
}

export default Players
