# ToDoList application
REST API for ToDo application

![alt text](https://media1.tenor.com/images/475f0154c049721e4b4ef126749e7aac/tenor.gif)


Go to project folder. Run - ```docker-compose up```

Localhost: http://localhost:8888/

Create a new user: 

```docker exec -ti nginx php bin/console fos:user:create user test2@example.com password```

Add role: ```docker exec -ti nginx php bin/console fos:user:promote user ROLE_READER```

Receive token: http://localhost:8888/login_check

Example:
```bash
curl -X POST http://127.0.0.1:8888/login_check -d _username=test -d _password=test
```

You will receive token in this format:

```json
{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1MzcxMTUwMjYsImV4cCI6MTUzNzExODYyNiwicm9sZXMiOlsiUk9MRV9SRUFERVIiLCJST0xFX1VTRVIiXSwidXNlcm5hbWUiOiJ0ZXN0In0.f_EWea3uhlYhABKGUAUUybDwaz9MoEgpIB3B9BXmtgwB63V2sqWhB-gtctD9AyuocYlCOs-jBA0Z2qBA8562dkx4y0cjmFy0g7VAIH2UwiMd8C8nwF3b1VW5RtmiGnVb_m5FDw1b4abSVEecsLRw2flHMEbMlRwY7TDwfxNc9cEIO0Scp08Al4f31Jdll71cgPOXkL9ZTJLxKlBHRoMlTCBw5JdQ0BQchrjCkV6-rHELUGiVlKLaU4BZIjZ29jhkWxLoBo-oCwTxFkrcIv9wOHhkIVFFLLmDWhlJMj5MEniQTfd6O-KIz3WyliqB8eFtkNRgPTCUN8No2jYoOQB4PIyvTTe3doGkSdDVmXRklz0bpywqbNiPhUl-DhDfgSRUOaxjUPx-L-Rb_p592OgdAfTIvHg5SER6tu5YjTP-ZOWv0jl3lOTWfROTHCsx2_rurfR4iVLSXz4PvmK-Ou48wz-xeONStbol0gg_6J-VPhREgHWRR5INL1Ji1wrfdyHSbUa2kxkyIuHagoy0cyX4OnN2bGlXTP-ruBs0ISCxHeMH4HuHt34PnrDJ_u5sCl1Ctvdx2IhT8YwQl1mEIvTye21tIHX0E7Zj39XIpXE0tOu46aJDj9Z7YKTbMocSGyJprO_rqor0NrnlrvZ69NkmCgofsgTLdKxW1lcqC7RCqQA"}
```


For working with tasks use following requests:
```bash
curl -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1MzcxMTEyMDIsImV4cCI6MTUzNzExNDgwMiwicm9sZXMiOlsiUk9MRV9SRUFERVIiLCJST0xFX1VTRVIiXSwidXNlcm5hbWUiOiJ0ZXN0In0.Lb5F6atqV_HxzfhGLlyPF3fgCGKF_2BWumdseDooveFX5faJ2yu1sHS32SRJ8L2VsywIZkssob8eBzeT__bLBehCp1nMgCa7FW1FcBuLod9fKX4mLzCf4m37y39h-mBJMBWsVMavklhgDjWeqSrH6x2Vz8l7pJqXCrUjoptO_zoZs8PIbZ09S09gwhkAql11GP-p4ILUzZAYP0s1cLbqgQoEIpYtlAgb9-e4VXs2po28lpS0lTlrWTYqI6H3G5NdaMrjPPRp3W123zXzZChX7PhoZ0-4MJ11Gf5BIQNhOCfgyzNpcfwc9OpkXPzZ7fbR19zFEjg4RP0tBfciZdQNgqlAp8LYI1ldZnLUYgsKUeY1jk36p7X4rGicJeN8B_YKKOednWwlcDRL77eE5-lh8U4E4gr8_dMYgDvmAXt_sX8XDYdMIfnohVAOSn5YwijXjoHCatmneJKv7Xcmy1q12aj7Qpso8yPgKvhMYYrWGsRfUvB0D2IfxK-q3B1DowIovJ_kOB7EJ0duaowO742FgJey_M64RFT21nfGw1lM6gUIxa4v_vEmhvjgm0ZwCkkJMXHXQ5iij9PfsyJpUB1nNvcLLsgrbmPoSHHSBdLjp6BEU-Wtade8-oGkTLv5_bdQUBdFzE1z3W_OXNvWOPSiAqqMOmDEL7v-oyHfuC-MWjE" http://localhost:8888/api/task/12
```

Or use POSTMAN settings (DELETE as example):

```bash
DELETE /api/task/3 HTTP/1.1
Host: localhost:8888
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1MzcxMTUwMjYsImV4cCI6MTUzNzExODYyNiwicm9sZXMiOlsiUk9MRV9SRUFERVIiLCJST0xFX1VTRVIiXSwidXNlcm5hbWUiOiJ0ZXN0In0.f_EWea3uhlYhABKGUAUUybDwaz9MoEgpIB3B9BXmtgwB63V2sqWhB-gtctD9AyuocYlCOs-jBA0Z2qBA8562dkx4y0cjmFy0g7VAIH2UwiMd8C8nwF3b1VW5RtmiGnVb_m5FDw1b4abSVEecsLRw2flHMEbMlRwY7TDwfxNc9cEIO0Scp08Al4f31Jdll71cgPOXkL9ZTJLxKlBHRoMlTCBw5JdQ0BQchrjCkV6-rHELUGiVlKLaU4BZIjZ29jhkWxLoBo-oCwTxFkrcIv9wOHhkIVFFLLmDWhlJMj5MEniQTfd6O-KIz3WyliqB8eFtkNRgPTCUN8No2jYoOQB4PIyvTTe3doGkSdDVmXRklz0bpywqbNiPhUl-DhDfgSRUOaxjUPx-L-Rb_p592OgdAfTIvHg5SER6tu5YjTP-ZOWv0jl3lOTWfROTHCsx2_rurfR4iVLSXz4PvmK-Ou48wz-xeONStbol0gg_6J-VPhREgHWRR5INL1Ji1wrfdyHSbUa2kxkyIuHagoy0cyX4OnN2bGlXTP-ruBs0ISCxHeMH4HuHt34PnrDJ_u5sCl1Ctvdx2IhT8YwQl1mEIvTye21tIHX0E7Zj39XIpXE0tOu46aJDj9Z7YKTbMocSGyJprO_rqor0NrnlrvZ69NkmCgofsgTLdKxW1lcqC7RCqQA
Cache-Control: no-cache
Postman-Token: 18499af8-91a6-97c4-2866-87cb584bdaf1
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW
```

## _______________TESTS

Run command ```docker exec -ti nginx php bin/console fos:user:create test test@example.com test```  in order to create test user.

Run tests: ``docker exec -ti nginx ./vendor/bin/simple-phpunit tests/AppBundle/Controller/TaskControllerTest.php``

```bash
PHPUnit 6.5.13 by Sebastian Bergmann and contributors.

Testing Tests\AppBundle\Controller\TaskControllerTest
....                                                                4 / 4 (100%)

Time: 2.86 seconds, Memory: 30.00MB

OK (4 tests, 22 assertions)
```

In current test we use HTTP_Authorization with jwt-authentication token. 