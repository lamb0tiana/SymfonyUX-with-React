import Layout from './Layout'
import AppRouter from './AppRouter'
import AuthWrapperContextComponent from '../context/authContext'
import { ApolloProvider } from '@apollo/client'
import client from '../apolloClient'
const App = () => (
  <ApolloProvider client={client}>
    <AuthWrapperContextComponent>
      <AppRouter />
    </AuthWrapperContextComponent>
  </ApolloProvider>
)
export default App
