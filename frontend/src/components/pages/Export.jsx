import {Button, Grid} from "@mui/material";
import {get, post} from "../../utils/request";
import {useState} from "react";
import ConfigSearch from "../Export/ConfigSearch";
import useSearch from "../Export/searchHook";

const Export = () => {

  const [typeId, setTypeId] = useState('');
  const [sourceType, setSourceType] = useState('simple');
  const [payload, setPayload] = useState(null);

  const component = useSearch(sourceType, payload, setPayload);

  const handleSearch = async () => {
    const response = await post(`export`, {typeId, payload});
    console.log(response);
  };

  return (
    <>
      <ConfigSearch
        typeId={typeId}
        setTypeId={setTypeId}
        sourceType={sourceType}
        setSourceType={setSourceType}
      />
      {
        component
      }
      <Grid item xs={3}>
        <Button variant="outlined" onClick={handleSearch}>Exportar</Button>
      </Grid>
    </>
  );
}

export default Export;