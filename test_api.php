<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://loja-veste-amor-production.up.railway.app/api/municipes/02944414097");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$headers = [
    "accept: application/json, text/plain, */*",
    "x-requested-with: XMLHttpRequest",
    "x-xsrf-token: eyJpdiI6Ikpla29pUGYwdk0rRFcvdVVQb0V0dEE9PSIsInZhbHVlIjoiZjRHZVhsb2MrVDlzbHlHMDRKRUtSZWl4U0krU0k0N1RPSDBxajlWd05TdVRhTWpSa25GYlVQYXk3RWdJWDZqVkR1R3RCMmNFb0NzZjJDU0JkSWxCc0NiQ2Q2Q1VLUkhSai83bE9GYXdRdkZwSkZQdmJQdU9US2JiRWl2ZGI5YlUiLCJtYWMiOiI5NDllNTllOGVkYjkwNmNiZTI1YTkyNWZkMjRmNDI1NDdlODI5Y2IyMGJlMDA0NGIzY2I2Mzk5MWM1MzE4NjgzIiwidGFnIjoiIn0=",
    "cookie: XSRF-TOKEN=eyJpdiI6Ikpla29pUGYwdk0rRFcvdVVQb0V0dEE9PSIsInZhbHVlIjoiZjRHZVhsb2MrVDlzbHlHMDRKRUtSZWl4U0krU0k0N1RPSDBxajlWd05TdVRhTWpSa25GYlVQYXk3RWdJWDZqVkR1R3RCMmNFb0NzZjJDU0JkSWxCc0NiQ2Q2Q1VLUkhSai83bE9GYXdRdkZwSkZQdmJQdU9US2JiRWl2ZGI5YlUiLCJtYWMiOiI5NDllNTllOGVkYjkwNmNiZTI1YTkyNWZkMjRmNDI1NDdlODI5Y2IyMGJlMDA0NGIzY2I2Mzk5MWM1MzE4NjgzIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6ImwyUVNzY05rd0tnZnJhV0Z3Rkdjb2c9PSIsInZhbHVlIjoidGhESUQrRW9WOEtPclhMZjU4c1ZacWl2Z05uQ2Y2aHYvejZuYzZ0cjFVNDkzUGhjVFJkb3E2OThiY2Z3Um5SUUE4ckJJL3JDa0F5ejAyUG1ZLytEU0dlSUtZVjI0bW1Tc2Rjbnl2NDJYdHA2WUpBT2tXWjBlN21YV2pKeGFkMDEiLCJtYWMiOiJlMmM0YjYzZTYwM2VjNDFmNzc2MWZhNDA0MmMyMzk1MmIzMzY0NGUyZjI2YzgyOGNlYTM4Nzc2YTZjYmZjN2NkIiwidGFnIjoiIn0%3D"
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP $httpcode\n";
echo $result;
curl_close($ch);
