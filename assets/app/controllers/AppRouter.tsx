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
    element: <Layout />,
    children: [
      {
        path: 'login',
        element: <Login />,
      },
      {
        path: 'team/:id',
        element: <Players />,
      },
      {
        index: true,
        element: <TeamDataTable />,
      },
    ],
  },
])

const AppRouter = () => {
  return <RouterProvider router={appRouter} fallbackElement={'loading'} />
}
export default AppRouter
