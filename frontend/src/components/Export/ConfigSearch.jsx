import {FormControl, Grid, InputLabel, MenuItem, Select} from "@mui/material";

const ConfigSearch = (
  {
    typeId,
    setTypeId,
    sourceType,
    setSourceType
  }
) => {
  return (
    <>
      <Grid item xs={6}>
        <FormControl fullWidth>
          <InputLabel id="label-seedId">Buscar por:</InputLabel>
          <Select
            labelId="label-seedId"
            value={typeId}
            label="Buscar por:"
            onChange={(e) => setTypeId(e.target.value)}
          >
            <MenuItem value="seedId">Id de semilla</MenuItem>
            <MenuItem value="groupId">Id de grupo</MenuItem>
          </Select>
        </FormControl>
      </Grid>
      <Grid item xs={6}>
        <FormControl fullWidth>
          <InputLabel id="label-type-search">Tipo de búsqueda</InputLabel>
          <Select
            labelId="label-type-search"
            value={sourceType}
            label="Tipo de búsqueda"
            onChange={(e) => setSourceType(e.target.value)}
          >
            <MenuItem value="simple">Digitar ID</MenuItem>
            <MenuItem value="csv">Cargar CSV</MenuItem>
          </Select>
        </FormControl>
      </Grid>
    </>
  );
}

export default ConfigSearch;