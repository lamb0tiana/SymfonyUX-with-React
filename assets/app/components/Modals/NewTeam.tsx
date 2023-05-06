import React, { ChangeEvent, useEffect, useState } from 'react'
import Box from '@mui/material/Box'
import Button from '@mui/material/Button'
import Modal from '@mui/material/Modal'
import {
  Autocomplete,
  FormControl,
  Input,
  InputAdornment,
  InputLabel,
  TextField,
} from '@mui/material'
import { getCodeList } from 'country-list'
import { doQuery, QueryMethod } from '../../utils'
import { Typography } from '@material-ui/core'
import { useAuth, validateToken } from '../../context/authContext'
import { useNavigate } from 'react-router-dom'
import Errors from '../Errors'

interface CountryNames<T extends string> {
  [key: string]: T
}
const countries: CountryNames<string> = getCodeList()

type NewDataTeamType = {
  name: string
  country: string
  balance: number | string
}
const NewTeam = ({ isOpen }) => {
  const [open, setOpen] = React.useState(isOpen)
  const handleClose = () => setOpen(false)
  const [errors, setErrors] = useState<string[]>([])
  const { dispatch, payloads } = useAuth()
  const navigate = useNavigate()

  const [formData, setFormData] = useState<NewDataTeamType>({
    balance: null,
    name: null,
    country: null,
  })

  const handleChangeField = (e: ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value })
  }

  const postTeam = async () => {
    const route = `${process.env.API_URL}/teams/create`
    const { country: countryCode, balance: money_balance, name } = formData
    const { data, status } = await doQuery(route, QueryMethod.POST, {
      name,
      money_balance: +money_balance.toString().replace(/\s/g, ''),
      countryCode: countryCode.toUpperCase(),
    })

    return { data, status }
  }

  const getRefreshedToken = async () => {
    const route = `${process.env.API_URL}/me`
    const { data, status } = await doQuery(route)
    if (status === 200) {
      return data
    }
    return { token: null }
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    const { data, status } = await postTeam()
    switch (status) {
      case 400:
        const _errors = data.map(({ message }) => message)
        setErrors(_errors)
        break
      case 201:
        const { token } = await getRefreshedToken()
        if (token) {
          validateToken(token) && dispatch({ token })
        } else {
          setErrors(['An error occured, please reload your page'])
        }
        break
    }
  }

  useEffect(() => {
    if (open && payloads.team?.id) {
      setOpen(false)
      navigate(`/team/${payloads.team.slug}`)
    }
  }, [payloads.team?.id])

  useEffect(() => {
    setErrors([])
  }, [formData])

  return (
    <div>
      <Modal
        onBackdropClick={(e) => setOpen(false)}
        open={open}
        aria-labelledby="modal-modal-title"
        aria-describedby="modal-modal-description"
      >
        <div
          style={{
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            backgroundColor: '#fff',
            padding: '2rem',
            borderRadius: '0.5rem',
          }}
        >
          <h2>Create your team</h2>
          <form onSubmit={handleSubmit}>
            <Errors errors={errors} />
            <FormControl fullWidth sx={{ m: 1 }} variant="standard">
              <InputLabel htmlFor="name">Name</InputLabel>
              <Input
                aria-required
                required
                autoComplete={'off'}
                id="name"
                onChange={handleChangeField}
                name="name"
                value={formData.name || ''}
              />
            </FormControl>
            <FormControl fullWidth sx={{ m: 1 }} variant="standard">
              <Autocomplete
                aria-required
                id="country-selection"
                isOptionEqualToValue={(option, value) => option[0] === value[0]}
                options={Object.entries(countries)}
                autoHighlight
                onChange={(e, value) => {
                  setFormData({ ...formData, country: value[0] })
                }}
                getOptionLabel={(option) => {
                  return option[1]
                }}
                renderOption={(props, option) => {
                  const [code, label] = option
                  return (
                    <Box
                      component="li"
                      sx={{ '& > img': { mr: 2, flexShrink: 0 } }}
                      {...props}
                    >
                      <img
                        loading="lazy"
                        width="20"
                        src={`https://flagcdn.com/w20/${code.toLowerCase()}.png`}
                        srcSet={`https://flagcdn.com/w40/${code.toLowerCase()}.png 2x`}
                        alt=""
                      />
                      <>{label}</>
                    </Box>
                  )
                }}
                renderInput={(params) => (
                  <TextField
                    required
                    aria-required
                    {...params}
                    label="Choose a country"
                    inputProps={{
                      ...params.inputProps,
                      autoComplete: 'off',
                    }}
                  />
                )}
              />
            </FormControl>

            <FormControl fullWidth sx={{ m: 1 }} variant="standard">
              <InputLabel htmlFor="standard-adornment-amount">
                {' '}
                Money balance
              </InputLabel>
              <Input
                required
                autoComplete={'off'}
                value={formData.balance || ''}
                id="standard-adornment-amount"
                startAdornment={
                  <InputAdornment position="start">$</InputAdornment>
                }
                name="balance"
                onChange={handleChangeField}
              />
            </FormControl>
            <FormControl>
              <Button
                variant="contained"
                type="submit"
                style={{ marginTop: '1rem' }}
              >
                Submit
              </Button>
            </FormControl>
          </form>
        </div>
      </Modal>
    </div>
  )
}

export default NewTeam
