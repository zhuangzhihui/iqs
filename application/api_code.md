## api 结构
```$xslt
{
'code':'XXX',
'message':'成功还是失败',
'data':    //返回的数据
}
```
正确的代码
-----
|  code      | 描述          | 备注                      |
| ---------- | -----------  | --------------           |
|  10000     | 表示成功      |    任何数据成功后都会       |
|            |              |                          |

错误的代码
---
|code|描述|备注|
|----|----|----|
|  40001   |  返回的数据为空    |  没有任何数据    |