import React, { ChangeEvent, useEffect, useState } from 'react'
import {
  Typography,
  TextField,
  FormControlLabel,
  Checkbox,
  Button,
  Link,
  Grid,
} from '@material-ui/core'
import axios from 'axios'
import { useAuth, validateToken } from '../context/authContext'
import { useNavigate } from 'react-router-dom'

const Login = () => {
  const [creds, setCredentials] = useState<{ email: string; password: string }>(
    { email: 'demo@dev.mg', password: 'demo' }
  )
  const { token, dispatch } = useAuth()
  const [error, setError] = useState<string>(null)
  const navigate = useNavigate()

  const handleSubmit = async (event) => {
    event.preventDefault()
    const credentialRoute = `${process.env.API_URL}/authentication`
    const { data, status } = await axios
      .post(credentialRoute, creds)
      .catch((e) => e.response)
    if (status !== 200) {
      setError(data.message)
    } else {
      const { token } = data
      validateToken(token) && dispatch({ token })
      navigate('/dashboard')
    }
  }

  const handleFieldChange = (e: ChangeEvent<HTMLInputElement>) => {
    const { name } = e.target
    setCredentials({ ...creds, [name]: e.target.value })
  }

  useEffect(() => {
    const token = localStorage.getItem('app_token')
    dispatch({ token })
  }, [])

  return (
    <div
      style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        height: '100vh',
      }}
    >
      <form onSubmit={handleSubmit} style={{ width: '100%', maxWidth: 400 }}>
        {error && (
          <Typography variant="body1" color="error" align={'center'}>
            {error}
          </Typography>
        )}
        <Typography variant="h4" align="center" gutterBottom>
          Manager login
        </Typography>
        <TextField
          value={creds.email}
          onChange={handleFieldChange}
          variant="outlined"
          margin="normal"
          fullWidth
          id="email"
          label="Email address"
          name="email"
          autoComplete="off"
          autoFocus
        />
        <TextField
          variant="outlined"
          margin="normal"
          fullWidth
          value={creds.password}
          onChange={handleFieldChange}
          name="password"
          label="Password"
          type="password"
          id="password"
          autoComplete="current-password"
        />

        <Button
          type="submit"
          fullWidth
          variant="contained"
          color="primary"
          style={{ marginTop: '1rem' }}
        >
          Login
        </Button>
        <Grid container style={{ marginTop: '1rem' }} justifyContent={'center'}>
          <Grid item>
            <Link href="#" variant="body2">
              New account ?
            </Link>
          </Grid>
        </Grid>
      </form>
    </div>
  )
}

export default Login
