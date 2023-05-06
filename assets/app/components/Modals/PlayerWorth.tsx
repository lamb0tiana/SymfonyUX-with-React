import React, { useEffect, useState } from 'react'
import Box from '@mui/material/Box'
import Button from '@mui/material/Button'
import Typography from '@mui/material/Typography'
import Modal from '@mui/material/Modal'
import {
  FilledInput,
  FormControl,
  Input,
  InputAdornment,
  InputLabel,
  TextField,
} from '@mui/material'

interface DefinitionWorthInterface {
  id: number
  worth: number | string
}

interface RefWorthModalRefInterface {
  handleOpen: (params: DefinitionWorthInterface) => void
}

const PlayerWorth = React.forwardRef<RefWorthModalRefInterface, {}>(
  (props, ref) => {
    const [open, setOpen] = React.useState(false)
    const handleClose = () => setOpen(false)
    const [data, setData] = useState<DefinitionWorthInterface>(null)
    React.useImperativeHandle(ref, () => {
      return {
        handleOpen: (data) => {
          setData(data)
          setOpen(true)
        },
      }
    })

    const handleSubmit = (e) => {
      e.preventDefault()
      console.log(data)
    }
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
                <InputLabel htmlFor="standard-adornment-amount">
                  Worth
                </InputLabel>
                <Input
                  id="standard-adornment-amount"
                  startAdornment={
                    <InputAdornment position="start">$</InputAdornment>
                  }
                  name="worth"
                  onChange={(e) =>
                    setData({
                      ...data,
                      worth: e.target.value,
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
  }
)

export default PlayerWorth
export { DefinitionWorthInterface, RefWorthModalRefInterface }
