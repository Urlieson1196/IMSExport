import React from 'react';
import AppBar from '@mui/material/AppBar';
import MenuIcon from '@mui/icons-material/Menu';

import PropTypes from 'prop-types';
import {Box, Button, IconButton, Toolbar, Typography} from "@mui/material";

const navItems = ['En curso', 'Exportados', 'Exportar'];


const Header = () => {
  return (
    <AppBar component="nav" sx={{mb: 2}}>
      <Toolbar>
        <IconButton
          color="inherit"
          aria-label="open drawer"
          edge="start"
          sx={{mr: 2, display: {sm: 'none'}}}
        >
          <MenuIcon/>
        </IconButton>
        <Typography
          variant="h6"
          component="div"
          sx={{flexGrow: 1, display: {xs: 'none', sm: 'block'}}}
        >
          Exportar Cursos
        </Typography>
        <Box sx={{display: {xs: 'none', sm: 'block'}}}>
          {navItems.map((item) => (
            <Button key={item} sx={{color: '#fff'}}>
              {item}
            </Button>
          ))}
        </Box>
      </Toolbar>
    </AppBar>
  );

}

Header.propTypes = {};

export default Header;
