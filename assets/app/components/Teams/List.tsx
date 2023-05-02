import React, { useMemo } from 'react'
import MaterialReactTable, { MRT_ColumnDef } from 'material-react-table'
export type Person = {
  firstName: string
  lastName: string
  email: string
  city: string
}

export const data: Person[] = [
  {
    firstName: 'Dylan',
    lastName: 'Murray',
    email: 'dmurray@yopmail.com',
    city: 'East Daphne',
  },
  {
    firstName: 'Raquel',
    lastName: 'Kohler',
    email: 'rkholer33@yopmail.com',
    city: 'Columbus',
  },
  {
    firstName: 'Ervin',
    lastName: 'Reinger',
    email: 'ereinger@mailinator.com',
    city: 'South Linda',
  },
  {
    firstName: 'Brittany',
    lastName: 'McCullough',
    email: 'bmccullough44@mailinator.com',
    city: 'Lincoln',
  },
  {
    firstName: 'Branson',
    lastName: 'Frami',
    email: 'bframi@yopmain.com',
    city: 'New York',
  },
  {
    firstName: 'Kevin',
    lastName: 'Klein',
    email: 'kklien@mailinator.com',
    city: 'Nebraska',
  },
]

const TeamList = () => {
  const columns = useMemo<MRT_ColumnDef<Person>[]>(
    //column definitions...
    () => [
      {
        accessorKey: 'firstName',
        header: 'First Name',
      },
      {
        accessorKey: 'lastName',
        header: 'Last Name',
      },
      {
        accessorKey: 'email',
        header: 'Email',
      },
      {
        accessorKey: 'city',
        header: 'City',
      },
    ],
    []
    //end
  )
  return (
    <div
      style={{
        margin: '50px',
        justifyContent: 'center',
        alignItems: 'center',
        height: '100vh',
      }}
    >
      <MaterialReactTable
        columns={columns}
        data={data}
        state={{ isLoading: false }}
      />
    </div>
  )
}

export default TeamList
