import React from 'react'
import {
  createBrowserRouter,
  RouterProvider,
  useLoaderData,
  useNavigate,
} from 'react-router-dom'
import Login from '../components/login'
import TeamDataTable from '../components/Teams/TeamDataTable'
import Players from '../components/Teams/Players'
import AuthWrapperContextComponent from '../context/authContext'
import LogoutIcon from '@mui/icons-material/Logout'
import {
  AppBar,
  Box,
  Button,
  IconButton,
  Link,
  Toolbar,
  Typography,
} from '@mui/material'
import Layout from './Layout'

const appRouter = createBrowserRouter([
  {
    path: '/',
    element: <TeamDataTable />,
  },
  {
    path: '/login',
    element: <Login />,
  },
  {
    path: '/dashboard/team/:id',
    element: <Players />,
  },
])

const AppRouter = () => {
  return (
    <AuthWrapperContextComponent>
      <RouterProvider router={appRouter} fallbackElement={'loading'} />
    </AuthWrapperContextComponent>
  )
}
export default AppRouter
