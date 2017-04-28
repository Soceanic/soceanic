import { Injectable } from '@angular/core';
import { Post } from './objects/post';
import { Http, Response, Headers, RequestOptions } from '@angular/http';

import { Observable } from 'rxjs/Observable';

import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/map';

@Injectable()
export class FeedService {

  constructor(private http: Http) { }

  private postUrl: string = 'http://vapeboyz.xyz/api/posts';
  private currUser = JSON.parse(localStorage.getItem('currentUser'))['currentUser'];

  getFeed(username?){
    if(username === undefined) var username = this.currUser;
    return this.http.get(`${this.postUrl}/${username}`)
                    .map(
                      (res: Response) => {
                        console.log(res.json());
                        return res.json();
                      }
                    )
                    .catch(
                      (err: any) => {
                        console.log('error getting posts in feed service');
                        return Observable.throw(err);
                      }
                    )
  }

}
