import {Pagination, Stack} from "@mui/material";
import * as PropTypes from 'prop-types';
import React from 'react';

export default function Paginator({page = 1, perPage = 20, totalPages = 1, handleSearch}) {

  return (
    <Stack sx={{mt: 1}} spacing={2}>
      {totalPages > 1 &&
        <Pagination
          onChange={(event, newPage) => handleSearch(newPage, perPage)}
          page={page}
          count={totalPages}/>
      }
    </Stack>
  );
}

Paginator.propType = {
  page: PropTypes.number,
  perPage: PropTypes.number,
  totalItems: PropTypes.number,
  totalPages: PropTypes.number,
};