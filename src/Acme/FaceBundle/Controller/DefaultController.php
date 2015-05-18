<?php

namespace Acme\FaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Acme\FaceBundle\Entity\Image;
use Acme\FaceBundle\Entity\ImageInfo;


/**
 * Class DefaultController
 * @Route("/")
 * @package Acme\FaceBundle\Controller
 */
class DefaultController extends Controller
{
    const BASE_URL   = 'http://face.lattecake.com/';
    const SOURCE_URL = 'http://source.lattecake.com/';

    /**
     * 首页
     * @Route("/", name="default_index")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'name' => 'hello world!'
        ];
    }

    /**
     * 显示图片页面
     * @Route("/image/{imageName}")
     * @param $imageName string
     * @Template()
     * @return array
     */
    public function imageAction($imageName)
    {
        /** 获取图片并展示 */
        $repository = $this->getDoctrine()
            ->getRepository('AcmeFaceBundle:Image');

        /** @var  $image \Acme\FaceBundle\Entity\Image */
        $image = $repository->findOneBy(
            array('name' => $imageName)
        );

        if (!$image) {
            throw $this->createNotFoundException(
                'No product found for image name ' . $imageName
            );
        }

        /** 获取该图片的相关信息 */
        $repository = $this->getDoctrine()->getRepository('AcmeFaceBundle:ImageInfo');
        /** @var  $imageInfo \Acme\FaceBundle\Entity\ImageInfo */
        $imageInfo = $repository->findOneBy([
            'imageId' => $image->getId()
        ]);

        $imageUrl = substr($imageName, 15, 2) . '/' . substr($imageName, 8, 2) . '/' . $imageName;

        $imageUrl = self::SOURCE_URL.'face/images/'.$imageUrl;

        /** 如果有该图片的信息话使用七牛的图片地址 */
        if ($imageInfo) {
            /** @var  $imageUrl */
//            $imageUrl = '';
        }
        $imageList = '';
        if( !empty($imageInfo) )
            $imageList = json_decode($imageInfo->getImageFaceInfo());

        return [
            'imageUrl'  => $imageUrl,
//            'imageUrl'  => 'http://www.faceplusplus.com.cn/wp-content/themes/faceplusplus/assets/img/demo/1.jpg',
            'image'     => $image,
            'imageInfo' => $imageInfo,
            'imageList' => $imageList
        ];
    }

    /**
     * 获取图片的详细信息
     * @Route("/imageInfo/{id}")
     * @param $id integer
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function imageInfoAction($id)
    {
        /** 获取图片并展示 */
        $repository = $this->getDoctrine()
            ->getRepository('AcmeFaceBundle:Image');
        /** @var  $image \Acme\FaceBundle\Entity\Image */
        $image = $repository->find($id);

        if (!$image) {
            throw $this->createNotFoundException(
                'No product found for image name '.$id
            );
        }

//        $appRoot = $this->get('kernel')->getRootDir();

        $imageUrl = substr($image->getName(), 15, 2) . '/' . substr($image->getName(), 8, 2) . '/' . $image->getName();

        /** 调用接口获取该图片的年龄相关信息 */
        $facePP = new \Facepp();
        $facePP->api_key       = $this->container->getParameter('facePP_key');
        $facePP->api_secret    = $this->container->getParameter('facePP_secret');

        $params['url']          = self::SOURCE_URL.'face/images/'.$imageUrl;
        $params['attribute']    = 'gender,age,race,smiling,glass,pose';
        $response               = $facePP->execute('/detection/detect',$params);
//        $source = str_replace('/app', '/web', $appRoot).$imageUrl;

//        print_r(getimagesize($source));die;

//        $params['img']          = $source;
//        $params['attribute']    = 'gender,age,race,smiling,glass,pose';
//        $response               = $facePP->execute('/detection/detect', $params);

        if( empty( $response ) )
        {
            throw $this->createNotFoundException(
                '调用API接口失败...'
            );
        }

        #json decode
        $data = json_decode($response['body'], 1);

        if( $response['http_code'] == 200 )
        {
            $face = $data['face'];

            /** 把图片的信息保存至imageInfo表里边 */
            /** @var  $imageInfo */
            $imageInfo = new ImageInfo();
            $imageInfo->setImageId( $image->getId() );
            $imageInfo->setSessionId( $data['session_id'] );
            $imageInfo->setImageHeight( $data['img_height'] );
            $imageInfo->setImageWidth( $data['img_width'] );
            $imageInfo->setImageImgId( $data['img_id'] );
            $imageInfo->setCreateAt(time());
            $imageInfo->setImageFaceInfo( json_encode($face) );

            $em = $this->getDoctrine()->getManager();

            $em->persist($imageInfo);
            $em->flush();
            $success = true;
            $message = '获取成功！';
            if( empty( $face ) )
            {
                $success = false;
                $message = '无法识别，请确认您上传的是脸而不是屁股....';
            }

            $jsonResponse = [
                'success'   => $success,
                'message'   => $message,
                'errorCode' => $response['http_code'],
                'data' => [
                    'faceInfo' => $face,
                ]
            ];
        }else
        {
            $jsonResponse = [
                'success'   => false,
                'message'   => $data['error'].'['.$data['error_code'].']',
                'errorCode' => $response['http_code'],
                'data'      => [
                ]
            ];
        }
        return new JsonResponse($jsonResponse);
    }

    /**
     * 上传图片
     * @Route("/uploadImage")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadImageAction(Request $request)
    {
        $fileUrl = $this->container->getParameter('qiNiuUrl');
//        $fileUrl = self::BASE_URL;
        if( $request->files )
        {
            $dir = './uploads/images/';
            /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
            foreach ($request->files as $file)
            {
                $name = md5( $file->getClientOriginalName(). microtime() ).'.'.$file->guessExtension();
                $path = substr($name, 15, 2).'/'.substr($name, 8, 2);
                $fileUrl = $fileUrl.'images/'.$path.'/'.$name;
                $fs = new Filesystem();
                $dir = $dir.$path;
                if( !$fs->exists( $dir ) )
                {
                    try {
                        $fs->mkdir( $dir );
                    } catch (IOExceptionInterface $e) {
                        echo "An error occurred while creating your directory at ".$e->getPath();
                    }
                }
                $file->move( $dir,  $name );

                list($width, $height, $type, $attr) = getimagesize($dir.'/'.$name);

                if( $width > 800 )
                {
                    $jsonResponse = [
                        'success'   => false,
                        'message'   => '请上传宽度小于800像素的屁股...',
                        'errorCode' => 'IMAGE_IS_MAX',
                        'data'      => [
                        ]
                    ];
                    return new JsonResponse($jsonResponse);
                }

                $image = new Image();
                $image->setName($name);
                $image->setCreateAt(time());
                $image->setSize($file->getClientSize());
                $image->setHeight($height);
                $image->setWidth($width);

                $em = $this->getDoctrine()->getManager();

                $em->persist($image);
                $em->flush();
                break;
            }
        }
        $response =  [
            'success'   => true,
            'message'   => '上传成功',
            'errorCode' => '00000',
            'data'      => [
                'imageUrl' => $fileUrl,
                'redirect' => $this->generateUrl('default_index').'image/'.$name
            ]
        ];
        echo json_encode($response);die;
//        return new JsonResponse($response);
    }
}
