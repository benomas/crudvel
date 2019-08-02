
expresion
    (a = 1 && b like 'ads' && (t = 3 || o = 33)) || (d < 3 && ( c > 4 || x < 3))
expresion prefija
    ||,&&,a = 1,b like 'ads,&&,||,t = 3,o = 33,&&,d < 3 ,||,c > 4,x < 3

json de expresion posfija
    [
        '|',
        '&',
        {o:'=',f:'a',v:1},
        {o:'like',f:'b',v:'ads'},
        '&',
        '|',
        {o:'=',f:'t',v:3},
        {o:'=',f:'o',v:33},
        '&',
        {o:'<',f:'d',v:3},
        '|',
        {o:'>',f:'c',v:4},
        {o:'<',x:'d',v:3},
    ]


