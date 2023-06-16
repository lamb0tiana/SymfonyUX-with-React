import React, { useEffect, useState } from 'react'
import Button from '@mui/material/Button'
import Modal from '@mui/material/Modal'
import { FormControl, Input, InputAdornment, InputLabel } from '@mui/material'
import { doQuery, QueryMethod } from '../../utils'
import { ModalRefreshlistInterface } from './NewPlayer'
import Errors from '../Errors'
import { useUpdateWorthMutation } from '../../../queries/graphql'

interface DefinitionWorthInterface {
  slug: string
  worth: number
  iri: string
}

interface RefWorthModalRefInterface {
  handleOpen: (params: DefinitionWorthInterface) => void
}

const PlayerWorth = React.forwardRef<
  RefWorthModalRefInterface,
  ModalRefreshlistInterface
>(({ refreshList }, ref) => {
  const [open, setOpen] = React.useState(false)
  const handleClose = () => setOpen(false)
  const [data, setData] = useState<DefinitionWorthInterface>(null)
  const [errors, setErrors] = useState<string[]>([])

  React.useImperativeHandle(ref, () => {
    return {
      handleOpen: (data) => {
        setData(data)
        setOpen(true)
      },
    }
  })

  const [setPlayerWorth, { data: _data, loading }] = useUpdateWorthMutation({
    variables: {
      input: {
        id: data?.iri,
        worth: data?.worth,
      },
    },
    onCompleted: (data) => {
      setOpen(false)
      refreshList()
    },
    onError: (error) => setErrors([error.message]),
  })

  const handleSubmit = async (e) => {
    e.preventDefault()
    setPlayerWorth()
  }

  useEffect(() => {
    setErrors([])
    console.log(data, data?.worth?.toLocaleString())
  }, [data])
  return (
    <div>
      <Modal
        open={open}
        onClose={handleClose}
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
          <h2>Set worth</h2>
          <form onSubmit={handleSubmit}>
            <FormControl fullWidth sx={{ m: 1 }} variant="standard">
              <InputLabel htmlFor="standard-adornment-amount">Worth</InputLabel>
              <Errors errors={errors} />
              <Input
                id="standard-adornment-amount"
                startAdornment={
                  <InputAdornment position="start">$</InputAdornment>
                }
                name="worth"
                onChange={({ target: { value } }) =>
                  setData({
                    ...data,
                    worth: +value.match(/\d/g).join(''),
                  })
                }
                value={data?.worth?.toLocaleString()}
              />
            </FormControl>

            <Button
              variant="contained"
              type="submit"
              style={{ marginTop: '1rem' }}
            >
              Submit
            </Button>
          </form>
        </div>
      </Modal>
    </div>
  )
})

export default PlayerWorth
export { DefinitionWorthInterface, RefWorthModalRefInterface }
