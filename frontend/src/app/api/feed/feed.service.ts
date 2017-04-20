import { Injectable } from '@angular/core';
import { Post } from './post';

@Injectable()
export class FeedService {

  private POSTS : [Post] = [
    {
      post_id: 10,
      username: 'borisirl',
      title: 'Test Post',
      text: 'If you can see this posts are somewhat working',
      attachment: 'lol what attachment',
      likes: 3
    },
    {
      post_id: 11,
      username: 'thakugan',
      title: 'Another test post',
      text: 'More posts are working!',
      attachment: 'lol what attachment',
      likes: 4
    },
    {
      post_id: 12,
      username: 'willposey',
      title: 'Yet Another Test Post',
      text: 'Yay this project is coming together A+ pls',
      attachment: 'lol what attachment',
      likes: 2
    },    {
          post_id: 10,
          username: 'borisirl',
          title: 'Test Post',
          text: 'If you can see this posts are somewhat working',
          attachment: 'lol what attachment',
          likes: 3
        },
        {
          post_id: 11,
          username: 'thakugan',
          title: 'Another test post',
          text: 'More posts are working!',
          attachment: 'lol what attachment',
          likes: 4
        },
        {
          post_id: 12,
          username: 'willposey',
          title: 'Yet Another Test Post',
          text: 'Yay this project is coming together A+ pls',
          attachment: 'lol what attachment',
          likes: 2
        }
  ];

  constructor() { }

  get(){
    return this.POSTS;
  }

  add(post: Post){
    this.POSTS.push(post);
  }

}
