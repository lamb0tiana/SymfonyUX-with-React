import { gql, useQuery } from '@apollo/client'

const GET_TEAMS = gql(`
  query teamList{
  
    team_collectionTeams(first: 1, after:"MA==") {
    edges {
      cursor
      node {
        id
        name
      }
    }
    pageInfo{
      endCursor
      startCursor
      hasNextPage
      hasPreviousPage
    }
  }
  }
`)
export { GET_TEAMS }
