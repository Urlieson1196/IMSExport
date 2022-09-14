import * as React from 'react';
import {useState} from 'react';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import Typography from '@mui/material/Typography';
import IconButton from '@mui/material/IconButton';
import MenuIcon from '@mui/icons-material/Menu';
import Menu from "./Menu";

const Header = () => {

  const [showMenu, setShowMenu] = useState(false);

  return (
    <Box sx={{flexGrow: 1}}>
      <AppBar position="static">
        <Toolbar variant="dense">
          <IconButton onClick={() => setShowMenu(true)} edge="start" color="inherit" aria-label="menu" sx={{mr: 2}}>
            <MenuIcon/>
          </IconButton>
          <Typography variant="h6" color="inherit" component="div">
            Exportación de cursos
          </Typography>
        </Toolbar>
      </AppBar>
      <Menu show={showMenu} setShow={setShowMenu}/>
    </Box>
  );
}

export default Header;