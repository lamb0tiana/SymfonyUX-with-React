import React, { ChangeEvent, useEffect, useState } from 'react'
import Box from '@mui/material/Box'
import Button from '@mui/material/Button'
import Typography from '@mui/material/Typography'
import Modal from '@mui/material/Modal'
import {
  Autocomplete,
  FilledInput,
  FormControl,
  Input,
  InputAdornment,
  InputLabel,
  TextField,
} from '@mui/material'
import { getCodeList } from 'country-list'

interface CountryNames<T extends string> {
  [key: string]: T
}
const countries: CountryNames<string> = getCodeList()

type NewDataTeamType = {
  name: string
  country: string
  balance: number | string
}
const CreateTeamModal = ({ isOpen }) => {
  const [open, setOpen] = React.useState(isOpen)
  const handleClose = () => setOpen(false)
  const [formData, setFormData] = useState<NewDataTeamType>({
    balance: null,
    name: null,
    country: null,
  })

  const handleChangeField = (e: ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value })
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    console.log(formData)
    setOpen(false)
  }
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
            <FormControl fullWidth sx={{ m: 1 }} variant="standard">
              <InputLabel htmlFor="name">Name</InputLabel>
              <Input
                autoComplete={'off'}
                id="name"
                onChange={handleChangeField}
                name="name"
                value={formData.name || ''}
              />
            </FormControl>
            <FormControl fullWidth sx={{ m: 1 }} variant="standard">
              <Autocomplete
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

export default CreateTeamModal
