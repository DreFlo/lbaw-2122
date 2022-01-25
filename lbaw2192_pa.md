# PA: Product and Presentation

The project's goal was to create a social network that could unite geeks across the world, with a particular focus on groups to allow people with common interests to come together.

# A9: Product

**Infra** is an easy to use social network that connects people.

## 1. Installation

The tag can be found [here](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2192/-/tags/PA).

The following command starts the Docker image:

``` bash
docker run -it -p 8000:80 --name=lbaw2192 -e DB_DATABASE="lbaw2192" -e DB_SCHEMA="lbaw2192" -e DB_USERNAME="lbaw2192" -e DB_PASSWORD="MruhkTYl" git.fe.up.pt:5050/lbaw/lbaw2122/lbaw2192
```

## 2. Usage

The final product can be found at: http://lbaw2192.lbaw.fe.up.pt

### 2.1. Administration and Credentials

An administrator logs in the same way as a regular user. There are a few pages only available to admins:
- View and administer all users: http://lbaw2192.lbaw.fe.up.pt/users
- View and administer all groups: http://lbaw2192.lbaw.fe.up.pt/groups
- View and administer all posts: http://lbaw2192.lbaw.fe.up.pt/posts

Otherwise an admin enjoys increased permissions throughout the platform.

|Email|Password|
|-|-|
|lbaw2192@gmail.com|pass|

### 2.2. User Credentials
|Type|Email|Password|
|-|-|-|
|Moderator (http://lbaw2192.lbaw.fe.up.pt/groups/2)|t@t.t|pass|
|Regular User|d@d.d|pass|

## 3. Application Help

For application help we have a [FAQ page](http://lbaw2192.lbaw.fe.up.pt/faq). It shows how to register an account and create a post.

## 4. Input Validation

### Client Side

There are required fields in all forms presented to the user.

### Server Side

When registering, logging in, and editing a user account certain fields are checked like the file type of the images, the age of the user and length of the password. When creating a new password we also check if the password field and the confirm password field are the same.

## 5. Check Accessibility and Usability

### 5.1. Accessibility
We scored **11/18**. The PDF can be found [here](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2192/-/blob/main/Checklists%20A9/Checklist%20de%20Acessibilidade%20-%20SAPO%20UX.pdf).
### 5.2. Usability
We scored **22/28**. The PDF can be found [here](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2192/-/blob/main/Checklists%20A9/Checklist%20de%20Usabilidade%20-%20SAPO%20UX.pdf).

## 6. HTML & CSS Validation

### 6.1. HTML

The HTML validation report can be found [here](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2192/-/blob/main/Checklists%20A9/HTML%20Validation.pdf).

### 6.2. CSS

The CSS validation report can be found [here](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2192/-/blob/main/Checklists%20A9/HTML%20Validation.pdf).

## 7. Revisions to the Project

Since the requirements specification stage we have implemented features related to interactions between users, friend requests can now be sent and handled as can membership requests and invites. A user can now also see their notifications, a moderators on a group can also see the groups notifications. Furthermore the search features on the website have also been improved. The general user experience on the site has also been improved by the use of anchors to other pages on the site and pages that describe why a user can't access a resource.
 
## 8. Implementation Details

### 8.1. Libraries Used

We used **Bootstrap**.

### 8.2. User Stories

| Identifier | Name | Module | Priority | State|Team Members|
|------------|------|-| ----------|-------------|-|
| US11 | See Welcome Screen | M05 | high | 100% |**Diogo Mendes**|
| US12 | Consult Contacts | M05 | high | 100% |**Diogo Mendes**|
| US13 | See About Us | M05 | high | 100% |**Diogo Mendes**|
| US14| See FAQ | M05 | high | 100% | **Tiago Rodrigues**, André Flores, Diogo Faria|
| US21 | Sign-in | M01 | high |100% |**Diogo Mendes**|
| US22 | Sign-up | M01 | high | 100% |**Diogo Mendes**, André Flores|
| US23 | Search for Public Groups | M06 | high | 100%|**Diogo Faria**|
| US24 | Search for Public Profiles | M06 | high | 100% |**Diogo Faria**|
| US25 | View Public Groups | M02 |  high | 100% |**Tiago Rodrigues**|
| US26 | View Public Profiles | M01 | high | 100% |**Diogo Mendes**|
| US27 | View Public Timeline | M01 | high | 100% |**Tiago Rodrigues**|
|US28|Recover Password|M01|high|95%|**Diogo Mendes**|
| US301 | Publish Post | M03 | high | 100% |**André Flores**|
| US302 | Delete Post | M03 | high | 100% |**André Flores**|
| US303 | Edit Post | M03 | high | 100% |**André Flores**|
| US304 | View User Profile | M01 | high | 100% |**Diogo Mendes**|
| US305 | Consult Feed | M01 | high | 100% |**Tiago Rodrigues**|
| US306 | Like Post | M03 | high | 100% |**André Flores**|
| US307 | Comment Post | M03 | high | 100% |**André Flores**|
| US308 | Remove Like From Post | M03 | high | 100% |**André Flores**|
| US309 | Remove Comment From Post | M03 | high |100% |**André Flores**|
| US310 | Edit Comment | M03 | high | 100% |**André Flores**|
| US311 | Like Comment | M03 |high | 100% |**André Flores**|
| US312 | Remove Like From Comment | M03 | high | 100% |**André Flores**|
| US313 | Search for User | M06 | high | 100% |**Diogo Faria**|
| US314 | Search for Group | M06 | high | 100% |**Diogo Faria**|
| US315 | Search for Post | M06 | high | 100% |**Diogo Faria**|
| US316 | Search for Comment | M06 | high | 95% |**Diogo Faria**|
| US317 | Log Out | M01 | high | 100% |**Diogo Mendes**|
| US318 | Delete Account | M01 | high | 100% |**Diogo Faria**, André Flores|
| US319 | View Personal Notifications | M04 | high | 100% |**André Flores**, Diogo Mendes|
|US322| Search Filters and Sorting|M06|high|100%|**Diogo Faria**|
| US401 | Profile | M01 | high | 100% |**Diogo Mendes**|
| US402 | Edit Profile | M01 | high | 100% |**Diogo Mendes**|
| US403 | Create Group | M02 |high | 100% |**Tiago Rodrigues**|
| US404 | Manage Group Invitation | M02 | high | 100% |**André Flores**, Diogo Mendes|
| US405 | Send Friend Requests | M01 | high | 100% |**Diogo Mendes**|
| US406 | Manage Received Friend Requests | M01 | high | 100% |**André Flores**, Diogo Mendes|
| US407 | Remove Friends | M01 | high | 100% |**Diogo Mendes**|
| US412 | Request to Join Private Group | M02 | high | 100% |**Tiago Rodrigues**|
| US413 | Join Public Group | M02 | high | 5% |**Tiago Rodrigues**|
| US51 | Make a Post in Group | M02 | high | 100% |**Tiago Rodrigues**, André Flores|
| US52 | View Group Members | M02 | high | 100% |**Tiago Rodrigues**|
| US53 | Leave Group | M02 | high | 5% |**Tiago Rodrigues**|
| US61 | Invite/Remove User from Group | M02 | high |55% | **Tiago Rodrigues** |
| US62 | Remove any Post from Group | M02 | high | 100% |**André Flores**|
| US63 | Remove any Comment on Post on Group | M02 | high | 100% |**André Flores**|
| US64 | Delete Group | M02 | high | 100% |**Tiago Rodrigues**, André Flores|
| US65 | Alter Group Information | M02 | high | 5% |**Tiago Rodrigues**|
| US71 | See All Profiles | M05 | high | 100% |**André Flores**|
| US72 | See All Groups | M05 | high | 100% |**André Flores**|
| US73 | Remove Post | M05 | high | 100% |**André Flores**|
| US74 | Remove Comment | M05 | high | 100% |**André Flores**|
| US75 | Remove Group | M05 | high | 100% |**Tiago Rodrigues**, André Flores|
| US76 | See All Posts | M05 | medium | 100% |**André Flores**|
| US408 | View Friends' Feed | M01 | medium | 0% ||
| US409 | Request to Join Public Group | M02 | medium | 0% ||
| US76 | Ban a User | M05 | medium | 100% |**André Flores**, Diogo Faria|
| US320 | Chat with other users | M01 | low | 0% ||
| US321 | Share Post | M03 | low | 70% |**André Flores**|
| US410 | Tag Friends in Post | M03 | low | 100% |**André Flores**|
| US411 | Tag Friends in Share | M03 | low | 100% |**André Flores**|
| US66 | Pin Post on Group | M02 | low | 0% ||
| US67 | Pin Comment on Post | M02 | low | 0% ||

- US28 - Works in the test environment but in the production environment the error _error:1416F086:SSL routines:tls_process_server_certificate:certificate verify failed_ occurs

# A10: Presentation

## 1. Product presentation

Infra is a social network with a focus on groups. A user can make posts on it, share other posts also comment on theirs and other user's posts and shares. A user can create groups and join other groups. Posts and comments can also be made specifically in a group. Users can also create friendships, a user will their friends' posts on their personal timeline (a user can only interact with a private user if they are their friend). User's may also interact with user content by liking and unliking it. A user will receive and be able to interact with notifications about various interactions (friend requests, likes on posts, etc.). Groups have moderators and there are also site-wide administrators capable of ensuring a good experience for all users on the platform. There are also powerful search capabilities on the site. 

## 2. Video Presentation
![](/Presentation%20screencaps/video%20screencap.png)
The video presentation can be found [here](https://drive.google.com/file/d/1nO6ZDdK-ycDA7DeXj4dF18Xq-ZRe1Ze9/view?usp=sharing).

## Revision history
---


GROUP2192, 21/01/2022

* André de Jesus Fernandes Flores, up201907001@edu.fe.up.pt
* Diogo Luís Araújo de Faria, up201907014@edu.fe.up.pt 
* Diogo Rafael Amorim Mendes, up201605360@edu.fe.up.pt
* Tiago André Batista Rodrigues, up201906807@edu.fe.up.pt (Editor)
