import { gql } from '@apollo/client';
import * as Apollo from '@apollo/client';
export type Maybe<T> = T | null;
export type InputMaybe<T> = Maybe<T>;
export type Exact<T extends { [key: string]: unknown }> = { [K in keyof T]: T[K] };
export type MakeOptional<T, K extends keyof T> = Omit<T, K> & { [SubKey in K]?: Maybe<T[SubKey]> };
export type MakeMaybe<T, K extends keyof T> = Omit<T, K> & { [SubKey in K]: Maybe<T[SubKey]> };
export type MakeEmpty<T extends { [key: string]: unknown }, K extends keyof T> = { [_ in K]?: never };
export type Incremental<T> = T | { [P in keyof T]?: P extends ' $fragmentName' | '__typename' ? T[P] : never };
const defaultOptions = {} as const;
/** All built-in and custom scalars, mapped to their actual values */
export type Scalars = {
  ID: { input: string | number; output: string; }
  String: { input: string; output: string; }
  Boolean: { input: boolean; output: boolean; }
  Int: { input: number; output: number; }
  Float: { input: number; output: number; }
};

export type AppAuthentication = Node & {
  __typename?: 'AppAuthentication';
  authPayloads: AuthUnion;
  id: Scalars['ID']['output'];
};

export type AuthUnion = Authenticated | FailureAuth;

export type Authenticated = {
  __typename?: 'Authenticated';
  token: Scalars['String']['output'];
};

export type FailureAuth = {
  __typename?: 'FailureAuth';
  error: Scalars['String']['output'];
};

export type Mutation = {
  __typename?: 'Mutation';
  /** _s a AppAuthentication. */
  _AppAuthentication?: Maybe<_AppAuthenticationPayload>;
};


export type Mutation_AppAuthenticationArgs = {
  input: _AppAuthenticationInput;
};

/** A node, according to the Relay specification. */
export type Node = {
  /** The id of this node. */
  id: Scalars['ID']['output'];
};

export type Query = {
  __typename?: 'Query';
  node?: Maybe<Node>;
  query_teamTeam?: Maybe<Team>;
  team_collectionTeams?: Maybe<TeamCursorConnection>;
};


export type QueryNodeArgs = {
  id: Scalars['ID']['input'];
};


export type QueryQuery_TeamTeamArgs = {
  id: Scalars['ID']['input'];
};


export type QueryTeam_CollectionTeamsArgs = {
  after?: InputMaybe<Scalars['String']['input']>;
  before?: InputMaybe<Scalars['String']['input']>;
  first?: InputMaybe<Scalars['Int']['input']>;
  last?: InputMaybe<Scalars['Int']['input']>;
};

export type Team = Node & {
  __typename?: 'Team';
  _id: Scalars['Int']['output'];
  countryCode: Scalars['String']['output'];
  createdAt: Scalars['String']['output'];
  id: Scalars['ID']['output'];
  moneyBalance: Scalars['Float']['output'];
  name: Scalars['String']['output'];
  slug: Scalars['String']['output'];
  updatedAt: Scalars['String']['output'];
};

/** Cursor connection for Team. */
export type TeamCursorConnection = {
  __typename?: 'TeamCursorConnection';
  edges?: Maybe<Array<Maybe<TeamEdge>>>;
  pageInfo: TeamPageInfo;
  totalCount: Scalars['Int']['output'];
};

/** Edge of Team. */
export type TeamEdge = {
  __typename?: 'TeamEdge';
  cursor: Scalars['String']['output'];
  node?: Maybe<Team>;
};

/** Information about the current page. */
export type TeamPageInfo = {
  __typename?: 'TeamPageInfo';
  endCursor?: Maybe<Scalars['String']['output']>;
  hasNextPage: Scalars['Boolean']['output'];
  hasPreviousPage: Scalars['Boolean']['output'];
  startCursor?: Maybe<Scalars['String']['output']>;
};

/** _s a AppAuthentication. */
export type _AppAuthenticationInput = {
  clientMutationId?: InputMaybe<Scalars['String']['input']>;
  /** User identifiant */
  email: Scalars['String']['input'];
  password: Scalars['String']['input'];
};

/** _s a AppAuthentication. */
export type _AppAuthenticationPayload = {
  __typename?: '_AppAuthenticationPayload';
  appAuthentication?: Maybe<AppAuthentication>;
  clientMutationId?: Maybe<Scalars['String']['output']>;
};

export type LoginMutationVariables = Exact<{
  input: _AppAuthenticationInput;
}>;


export type LoginMutation = { __typename?: 'Mutation', _AppAuthentication?: { __typename?: '_AppAuthenticationPayload', appAuthentication?: { __typename?: 'AppAuthentication', authPayloads: { __typename?: 'Authenticated', token: string } | { __typename?: 'FailureAuth', error: string } } | null } | null };

export type TeamListQueryVariables = Exact<{
  count: Scalars['Int']['input'];
  cursor?: InputMaybe<Scalars['String']['input']>;
}>;


export type TeamListQuery = { __typename?: 'Query', team_collectionTeams?: { __typename?: 'TeamCursorConnection', totalCount: number, edges?: Array<{ __typename?: 'TeamEdge', cursor: string, node?: { __typename?: 'Team', slug: string, name: string, id: number, isocode: string, funds: number } | null } | null> | null } | null };


export const LoginDocument = gql`
    mutation login($input: _AppAuthenticationInput!) {
  _AppAuthentication(input: $input) {
    appAuthentication {
      authPayloads {
        ... on Authenticated {
          token
        }
        ... on FailureAuth {
          error
        }
      }
    }
  }
}
    `;
export type LoginMutationFn = Apollo.MutationFunction<LoginMutation, LoginMutationVariables>;

/**
 * __useLoginMutation__
 *
 * To run a mutation, you first call `useLoginMutation` within a React component and pass it any options that fit your needs.
 * When your component renders, `useLoginMutation` returns a tuple that includes:
 * - A mutate function that you can call at any time to execute the mutation
 * - An object with fields that represent the current status of the mutation's execution
 *
 * @param baseOptions options that will be passed into the mutation, supported options are listed on: https://www.apollographql.com/docs/react/api/react-hooks/#options-2;
 *
 * @example
 * const [loginMutation, { data, loading, error }] = useLoginMutation({
 *   variables: {
 *      input: // value for 'input'
 *   },
 * });
 */
export function useLoginMutation(baseOptions?: Apollo.MutationHookOptions<LoginMutation, LoginMutationVariables>) {
        const options = {...defaultOptions, ...baseOptions}
        return Apollo.useMutation<LoginMutation, LoginMutationVariables>(LoginDocument, options);
      }
export type LoginMutationHookResult = ReturnType<typeof useLoginMutation>;
export type LoginMutationResult = Apollo.MutationResult<LoginMutation>;
export type LoginMutationOptions = Apollo.BaseMutationOptions<LoginMutation, LoginMutationVariables>;
export const TeamListDocument = gql`
    query teamList($count: Int!, $cursor: String = "LTE=") {
  team_collectionTeams(first: $count, after: $cursor) {
    totalCount
    edges {
      cursor
      node {
        id: _id
        slug
        name
        isocode: countryCode
        funds: moneyBalance
      }
    }
  }
}
    `;

/**
 * __useTeamListQuery__
 *
 * To run a query within a React component, call `useTeamListQuery` and pass it any options that fit your needs.
 * When your component renders, `useTeamListQuery` returns an object from Apollo Client that contains loading, error, and data properties
 * you can use to render your UI.
 *
 * @param baseOptions options that will be passed into the query, supported options are listed on: https://www.apollographql.com/docs/react/api/react-hooks/#options;
 *
 * @example
 * const { data, loading, error } = useTeamListQuery({
 *   variables: {
 *      count: // value for 'count'
 *      cursor: // value for 'cursor'
 *   },
 * });
 */
export function useTeamListQuery(baseOptions: Apollo.QueryHookOptions<TeamListQuery, TeamListQueryVariables>) {
        const options = {...defaultOptions, ...baseOptions}
        return Apollo.useQuery<TeamListQuery, TeamListQueryVariables>(TeamListDocument, options);
      }
export function useTeamListLazyQuery(baseOptions?: Apollo.LazyQueryHookOptions<TeamListQuery, TeamListQueryVariables>) {
          const options = {...defaultOptions, ...baseOptions}
          return Apollo.useLazyQuery<TeamListQuery, TeamListQueryVariables>(TeamListDocument, options);
        }
export type TeamListQueryHookResult = ReturnType<typeof useTeamListQuery>;
export type TeamListLazyQueryHookResult = ReturnType<typeof useTeamListLazyQuery>;
export type TeamListQueryResult = Apollo.QueryResult<TeamListQuery, TeamListQueryVariables>;