import * as React from 'react';
import {useEffect, useState} from 'react';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import {v4 as uid} from 'uuid';
import * as PropTypes from 'prop-types';
import {Alert} from "@mui/material";
import Paginator from "./Paginator";
import Box from "@mui/material/Box";

export default function MyTable(
  {
    headers = [],
    data = [],
    pagination,
    actions = [],
    handleSearch
  }) {

  const [rows, setRows] = useState([]);

  useEffect(() => {
    const newData = data?.map((row) => {
      const temp = {...row};
      for (const attr in headers) {
        temp[attr] = row[attr];
      }
      return temp;
    });
    setRows(newData);
  }, [data]);

  const drawBody = (row) => {

    return (
      <>
        {
          Object.keys(headers).map(header => {
              const alt = row[`${header}-long`] ? row[`${header}-long`] : row[header];
              return <TableCell
                alt={alt}
                title={alt}
                key={uid()}
                sx={{textAlign: 'center'}}
              >
                {String(row[header]).length > 50 ? `${String(row[header])?.substring(0, 50)} ...` : String(row[header])}
              </TableCell>
            }
          )
        }
        {
          actions.map((action) =>
            <TableCell
              key={uid()}
              sx={{textAlign: 'center'}}
            >
              {action.component(row)}
            </TableCell>
          )}
      </>
    );
  };

  return (
    <>
      {
        data?.length <= 0 ?
          <Alert severity={"warning"}>
            No hay datos para mostrar
          </Alert> :
          <>
            <Box sx={{p:1}}>
              <TableContainer>
                <Table sx={{minWidth: 650}} aria-label="simple table">
                  <TableHead>
                    <TableRow>
                      {
                        Object.values(headers).map((header, index) =>
                          <TableCell
                            sx={{textAlign: 'center', fontWeight: 'bold'}}
                            key={index}>
                            {header}
                          </TableCell>)
                      }
                      {
                        actions.map((action, index) =>
                          <TableCell
                            sx={{textAlign: 'center', fontWeight: 'bold'}}
                            key={index}>
                            {action.title}
                          </TableCell>)
                      }
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {rows?.map((row) => (
                      <TableRow
                        key={uid()}
                        sx={{'&:last-child td, &:last-child th': {border: 0}}}
                      >
                        {drawBody(row)}
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </TableContainer>
            </Box>
            <Paginator
              page={pagination?.page}
              totalPages={pagination?.totalPages ?? 1}
              handleSearch={handleSearch}
              perPage={pagination?.perPage}
            />
          </>
      }
    </>
  );
}

Table.propTypes = {
  headers: PropTypes.object,
  data: PropTypes.array,
  pagination: PropTypes.object,
  actions: PropTypes.array,
  handleSearch: PropTypes.func,
};

Table.defaultProps = {
  headers: {},
  data: [],
  actions: []
};
