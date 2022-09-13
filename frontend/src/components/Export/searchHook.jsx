import CSVReader from "../commons/CSVReader";
import {utf8Decode} from "../../utils/tools";
import {myAlert} from "../../utils/alerts";
import {Box, Grid, TextField} from "@mui/material";
import {useEffect} from "react";

const useSearch = (sourceType, payload, setPayload) => {
  useEffect(() => {
    setPayload(null)
  }, [sourceType]);


  const handleHeader = (rows = []) => {
    const emails = [];
    if (rows[0].length > 1000) {
      return myAlert('Ha excedido el límite máximo de correos. El límite es 1000 correos')
    }
    for (let i = 0; i < rows[0].length; i++) {
      if (!emails.find(email => email.email === rows[1][i])) {
        const name = rows[0][i];
        const email = rows[1][i];
        if (name !== '' && email !== '') {
          emails.push({
            name: utf8Decode(name),
            email
          });
        }
      }
    }
    setPayload(emails)
  }


  if (sourceType === 'csv') {
    return (<Grid item xs={4}>
      <CSVReader handleReader={handleHeader}/>
    </Grid>)
  }
  return (<Grid item xs={4}>
    <Box
      component="form"
      noValidate
      autoComplete="off"
    >
      <TextField
        fullWidth
        id="id"
        label="Id de semilla"
        variant="outlined"
        value={payload}
        onChange={(e) => {
          setPayload(e.target.value);
        }}
      />
    </Box>
  </Grid>)
}

export default useSearch;