import React from 'react'
import {
  createBrowserRouter,
  RouterProvider,
  useLoaderData,
} from 'react-router-dom'
import App from './App'
import Login from '../components/login'
import TeamDataTable from '../components/Teams/TeamDataTable'
import Players from '../components/Teams/Players'
const appRouter = createBrowserRouter([
  {
    path: '/',
    element: <App />,
  },
  {
    path: '/dashboard',
    element: <TeamDataTable />,
  },
  {
    path: '/dashboard/team/:id',
    element: <Players />,
  },
])

const AppRouter = () => (
  <RouterProvider router={appRouter} fallbackElement={'loading'} />
)
export default AppRouter
