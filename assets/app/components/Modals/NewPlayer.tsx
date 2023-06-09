import React, { ChangeEvent, useEffect, useState } from 'react'
import Button from '@mui/material/Button'
import Modal from '@mui/material/Modal'
import { FormControl, Input, InputLabel } from '@mui/material'
import { getCodeList } from 'country-list'
import { doQuery, QueryMethod } from '../../utils'
import { Typography } from '@material-ui/core'
import { useAuth } from '../../context/authContext'
import { useNavigate } from 'react-router-dom'
import Errors from '../Errors'
import { useCreatePlayerMutation } from '../../../queries/graphql'

interface CountryNames<T extends string> {
  [key: string]: T
}
const countries: CountryNames<string> = getCodeList()

type NewDataPlayerType = {
  name: string
  surname: string
}
interface RefNewPlayerInterface {
  openModal: () => void
}

interface ModalRefreshlistInterface {
  refreshList: () => void
}

const NewPlayer = React.forwardRef<
  RefNewPlayerInterface,
  ModalRefreshlistInterface
>(({ refreshList }, ref) => {
  const [open, setOpen] = React.useState(false)
  const handleClose = () => setOpen(false)
  const [errors, setErrors] = useState<string[]>([])
  const { dispatch, payloads } = useAuth()
  const navigate = useNavigate()

  const [formData, setFormData] = useState<NewDataPlayerType>({
    surname: null,
    name: null,
  })

  const handleChangeField = (e: ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value })
  }
  const [createPlayer] = useCreatePlayerMutation({
    variables: { input: formData },
    onCompleted: () => {
      setOpen(false)
      refreshList()
    },
    onError: (error) => {
      setErrors([error.message])
    },
  })

  const handleSubmit = async (e) => {
    e.preventDefault()
    createPlayer()
  }

  React.useImperativeHandle(ref, () => {
    return {
      openModal: () => setOpen(true),
    }
  })
  useEffect(() => {
    if (open === false) {
      setFormData({
        name: null,
        surname: null,
      })
    }
  }, [open])

  useEffect(() => {
    setErrors([])
  }, [formData])

  return (
    <div>
      <Modal
        open={open}
        onBackdropClick={() => setOpen(false)}
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
          <h2>Add player in your team</h2>
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
              <InputLabel htmlFor="name">Surname</InputLabel>
              <Input
                aria-required
                required
                autoComplete={'off'}
                id="surname"
                onChange={handleChangeField}
                name="surname"
                value={formData.surname || ''}
              />
            </FormControl>

            <FormControl>
              <Button
                variant="contained"
                type="submit"
                style={{ marginTop: '1rem' }}
              >
                Add player
              </Button>
            </FormControl>
          </form>
        </div>
      </Modal>
    </div>
  )
})

export default NewPlayer
export { RefNewPlayerInterface, NewDataPlayerType, ModalRefreshlistInterface }
