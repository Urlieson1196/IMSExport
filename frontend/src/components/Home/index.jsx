import React from 'react';
import {Card, CardContent, Container, Grid} from "@mui/material";
import {Outlet} from 'react-router-dom';

const Body = () => {
  return (
    <Container sx={{marginTop: '6em'}}>
      <Card elevation={3}>
        <CardContent>
          <Grid container spacing={2}>
            <Outlet/>
          </Grid>
        </CardContent>
      </Card>
    </Container>
  );
}

Body.propTypes = {};

export default Body;
