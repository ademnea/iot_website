<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News form</title>
  <style>
    form {
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f2f2f2;
      border-radius: 10px;
      box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
    }
    
    label {
      display: inline-block;
      width: 150px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    
    input[type="text"],
    input[type="number"],
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: none;
      border-radius: 5px;
      background-color: #ffffff;
      box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
    }
    
    input[type="file"] {
      margin-top: 10px;
    }
    
    input[type="submit"] {
      display: inline-block;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: #ffffff;
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      text-decoration: none;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    
    input[type="submit"]:hover {
      background-color: #3e8e41;
    }
    
  </style>
</head>
<body>

   @include('sidebar')
   <br><br>
    <center>
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl light:text-white">
           News form
        </h1>
    </center>
    <br>
    <form action="/insert_news" method="post" enctype="multipart/form-data">
    @csrf
    
      <input type="hidden" name="admin" value="{{ Session::get('id') }}">
      
      <label for="venue">Event title:</label><br/>
      <input type="text" id="venue" name="title" placeholder="title" required><br>

      <label for="description">News Description:</label><br/>
      <textarea id="description" name="description" rows="4" cols="50" required>
      </textarea><br>
    
      <label for="participants">Venue:</label><br/>
      <input type="text" id="participants" placeholder="venue" name="venue" required><br>

      <label for="participants">Article Link</label><br/>
      <input type="text" id="" name="link" placeholder="article link"><br>
    
      <label for="duration"> Start Date:</label><br/>
      <input type="date" id="start_date" name="start_date" required><br><br>

            <label for="duration"> End Date:</label><br/>
      <input type="date" id="end_date" name="end_date" required><br><br>

      <label for="duration">Image:</label><br/>
      <input type="file" id="duration" name="image" required><br><br>
    
      <br><br>
    
      <input type="submit" value="Add News">
    </form>
 
</body>
</html>