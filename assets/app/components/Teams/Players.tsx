import { useParams } from 'react-router-dom'
import React, { useEffect, useRef, useState } from 'react'
import { Button, Grid, Typography } from '@mui/material'
import Loader from '../Loader'
import Paper from '@mui/material/Paper'
import TableContainer from '@mui/material/TableContainer'
import Table from '@mui/material/Table'
import TableHead from '@mui/material/TableHead'
import TableRow from '@mui/material/TableRow'
import TableCell from '@mui/material/TableCell'
import TableBody from '@mui/material/TableBody'
import { doQuery, QueryMethod } from '../../utils'
import { useAuth } from '../../context/authContext'
import PlayerWorth, { RefWorthModalRefInterface } from '../Modals/PlayerWorth'
import NewTeam from '../Modals/NewTeam'
import NewPlayer, { RefNewPlayerInterface } from '../Modals/NewPlayer'
import Errors from '../Errors'

type PlayerType = {
  id: number
  name: string
  surname: string
  worth: number
  slug: string
}
const Players = () => {
  const { slug } = useParams()
  const [isFetchingData, setIsFetchingData] = useState(false)
  const [data, setData] = useState<PlayerType[]>([])
  const { dispatch, payloads, token } = useAuth()
  const [isAddPlayer, setIsAddPlayer] = useState<boolean>(false)
  const hasTeam: boolean = payloads?.team?.id != null
  const [isOwner, setIsOwner] = useState<boolean>(false)
  const [errors, setErrors] = useState<string[]>([])
  const fetchingData = () => {
    const url = `${process.env.API_URL}/teams/${slug}/players`
    setIsFetchingData(true)
    doQuery(url).then(({ data }) => {
      setData(data)
      setIsFetchingData(false)
    })
  }
  useEffect(() => {
    dispatch({ token: localStorage.getItem('app_token') })
    fetchingData()
  }, [])

  useEffect(() => {
    setIsOwner(slug === payloads?.team?.slug)
  }, [payloads])

  const PlayerWorthRef: React.RefObject<RefWorthModalRefInterface> =
    useRef(null)

  const newPlayerRef: React.RefObject<RefNewPlayerInterface> = useRef(null)

  const setWorth = ({ worth, slug }) =>
    PlayerWorthRef.current.handleOpen({
      slug,
      worth,
    })

  const buyPlayer = async ({ worth, slug }) => {
    const route = `${process.env.API_URL}/teams/setPlayer/${slug}`
    setErrors([])
    const { status, data: response } = await doQuery(route, QueryMethod.POST, {
      transfert_amount: worth,
    })
    if (status === 400) {
      const _errors = response.map(({ message }) => message)
      setErrors(_errors)
    } else if (status === 201) {
      fetchingData()
    }
  }
  const handleClick = ({ worth, slug }) => {
    isOwner ? setWorth({ worth, slug }) : buyPlayer({ worth, slug })
  }
  return (
    <Grid textAlign={'center'}>
      <Typography
        component={'h3'}
        sx={{
          marginTop: '150px!important',
          fontSize: '3rem!important',
          fontWeight: 'bold!important',
        }}
      >
        {isOwner
          ? 'Your teams'
          : `Players of team ${payloads?.team?.name || ''}`}
      </Typography>
      <Errors errors={errors} />
      {isOwner && hasTeam ? (
        <Button
          size={'small'}
          variant="contained"
          color="primary"
          style={{ marginTop: '1rem' }}
          onClick={() => newPlayerRef.current.openModal()}
        >
          Add player
        </Button>
      ) : (
        ''
      )}
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
                  {data.map(({ id, name, surname, worth, slug }) => (
                    <TableRow key={id}>
                      <TableCell>{name}</TableCell>
                      <TableCell>{surname}</TableCell>
                      <TableCell>
                        {!isOwner && !worth ? (
                          '-'
                        ) : (
                          <Button
                            color={isOwner ? 'success' : 'primary'}
                            variant="contained"
                            size={'small'}
                            disabled={!hasTeam}
                            onClick={(e) => handleClick({ worth, slug })}
                          >
                            {`${
                              isOwner
                                ? worth
                                  ? 'Edit worth'
                                  : 'Sell player'
                                : 'Buy player'
                            }`}
                          </Button>
                        )}
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
      <PlayerWorth ref={PlayerWorthRef} refreshList={fetchingData} />
      {token ? <NewTeam isOpen={!payloads?.team?.id} /> : ''}
      <NewPlayer ref={newPlayerRef} refreshList={fetchingData} />
    </Grid>
  )
}

export default Players
