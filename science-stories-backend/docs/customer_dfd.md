graph TD
1(Start) --> 2(Sign up)
1(Start) --> 3(Login)
2(Sign up) -->4{Success}
4{Success}-->3(Login)
2(Sign up)-->6(Fail)
3(Login)-->7{Success}
3(Login)-->6(Fail)
6(Fail)-->1(Start)
7{Success}-->8(Payment Setup)
8(Payment Setup)-->9(Home Screen)
9(Home Screen)-->10(Book Shelf)
9(Home Screen)-->11(Favourites)
9(Home Screen)-->12(Downloads)
9(Home Screen)-->13(App Purchases)
9(Home Screen)-->14(Notification)
9(Home Screen)-->15(Settings)
10(Book Shelf)-->16(Book Preview)
16(Book Preview)-->17(Purchase)
16(Book Preview)-->18(Ratings)
16(Book Preview)-->19(Likes)
16(Book Preview)-->23(language)
16(Book Preview)-->20(Download)
17(Purchase)-->21(Self)
17(Purchase)-->31(Gift)
15(Settings)-->24(Profile Setting)
15(Settings)-->25(Language Setting)
15(Settings)-->26(Other Setting)
24(Profile Setting)-->27(Purchase History)
24(Profile Setting)-->28(BookMarks)
24(Profile Setting)-->29(App Setting)
25(Language Setting)-->30(Change Language)
14(Notification)-->32(Updates)
14(Notification)-->33(Announcements)
15(Settings)-->34(Logout)
34(Logout)-->3(Login)



