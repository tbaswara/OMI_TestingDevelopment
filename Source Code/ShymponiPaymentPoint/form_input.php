<html>
<head>
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

li {
    display: inline;
}
</style>
</head>
<body>
<ul>
    <li><a href="form_input.php">Prepaid</a></li>
    <li><a href="form_input_postpaid.php">Postpaid</a></li>
    <li><a href="form_input_nontaglis.php">Nontaglis</a></li>
</ul>
    
    
    
    <h3>Transaksi Prepaid</h3>
    <form action="RunSyhmpony.php">
  <input type="text" name="nometer_idpel" />
  <select name="flag">
  <option value="0" name="flag">No Meter</option>
  <option value="1" name="flag">ID Pelanggan</option>
  </select>
  
  <input type="submit" value="Inquiry" />
  </form>
</body>
</html>
        
   