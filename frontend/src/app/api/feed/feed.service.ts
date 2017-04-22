import { Injectable } from '@angular/core';
import { Post } from './post';
import { Http, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/map';

@Injectable()
export class FeedService {

  constructor() { }

  private postsUrl: string = 'app/api/feed/posts.json';

  get(): Observable<[Post]> {
    return this.http.get(this.postsUrl)
                    .map(this.extractData)
                    .catch(this.handleError)
  }

  add(post: Post): Observable<Post> {
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.http.post(this.postsUrl, JSON.stringify(post), options)
                    .map(this.extractData)
                    .catch(this.handleError)

  }

  private extractData(res: Response){
    let body =  res.json();
    return body.data || {};
  }

  private handleError(error: Response | any){
    let errMsg: string;
    if (error instanceof Response) {
      const body = error.json() || '';
      const err = body.error || JSON.stringify(body);
      errMsg = `${error.status} - ${error.statusText || ''} ${err}`;
    } else {
      errMsg = error.message ? error.message : error.toString();
    }
    console.error(errMsg);
    return Observable.throw(errMsg);
  }
}

}
