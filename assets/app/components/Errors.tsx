import { Typography } from '@material-ui/core'
import React from 'react'

const Errors: React.FC<{ errors: string[] }> = ({ errors = [] }) => (
  <>
    {errors.map((error, index) => (
      <Typography key={index} variant="body1" color="error" align={'center'}>
        {error}
      </Typography>
    ))}
  </>
)

export default Errors
