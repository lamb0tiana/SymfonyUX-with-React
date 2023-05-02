import { MRT_ColumnDef } from 'material-react-table'
type Player = {
  firstName: string
  surname: string
  team: string
}
const columns: MRT_ColumnDef<Player>[] = [
  {
    accessorKey: 'team',
    header: 'team',
  },
  {
    accessorKey: 'firstName',
    header: 'First Name',
  },
  {
    accessorKey: 'surname',
    header: 'Surname',
  },
]

export { Player, columns }
