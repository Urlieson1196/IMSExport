<?php

namespace IMSExport\Application\Repositories;

use IMSExport\Core\Connection\BaseModel;

class Group extends BaseModel
{
    public function find($id): array|null
    {
        $sql = "select 
			g.idgrupo as id,
			g.nombre as title,
			g.nombre as description,
			g.siglas as seedId,
			g.fk_idusuarioSocial as socialId
			from grupo g where siglas = :id;";
        return $this->query($sql, ['id' => $id]);
    }

    public function searchFolders(int $groupId): array|null
    {
        $sql = "
            select * from temas_clase where idclase = :groupId
        ";
        return $this->query($sql, compact('groupId'));
    }

    public function getScaffolding($groupId): ?array
    {
        $sql = "
            (SELECT idTema as id, nombre as title, idPadre as parent_id, 'link' as link, 'folder' as `type`, null as resourceType
			FROM temas_clase WHERE idClase={$groupId} 
			AND (fechaApertura IS NULL or fechaApertura<DATE_SUB(NOW(), INTERVAL 5 HOUR))  )					
			union					
		  	(SELECT idarchivo as id, p.contenido as title, p.fk_idTema as parent_id, 'link' as link, 'file' as `type`, null as resourceType
			FROM (post as p, archivo a)
			inner join  temas_clase as  tc on (p.fk_idTema=tc.idtema AND tc.idclase={$groupId})
			inner join grupo g on (p.fk_usuariosocial_destino = g.fk_idusuarioSocial AND g.idgrupo = {$groupId})
			WHERE
			p.idpost=a.post_idpost
			group by p.idpost)			
			union			
		  	(SELECT p.idpost as id, e.titulo as title, p.fk_idTema as parent_id, 'link' as link, 'file' as `type`, null as resourceType
			FROM (post as p, examen e)
			inner join temas_clase tc on (p.fk_idTema = tc.idtema AND tc.idclase={$groupId})
			inner join grupo g on (p.fk_usuarioSocial_destino = g.fk_idusuarioSocial AND g.idgrupo={$groupId})
			WHERE p.idpost=e.idpost and p.enlace != 2 and e.tipoExamen='exams'
			AND p.idpost NOT IN (SELECT tm.fk_idPost FROM post p INNER JOIN tcu_modulo tm ON tm.fk_idContenido = p.idpost WHERE p.fk_usuarioSocial_destino = (SELECT fk_idusuarioSocial FROM grupo WHERE idgrupo = {$groupId}))
			group by e.titulo)			
			union			
		  	(SELECT p.idpost as id, e.titulo as title, p.fk_idTema as parent_id, 'link' as link, 'file' as `type`, null as resourceType
			FROM (post as p, examen e)
			inner join temas_clase tc on (p.fk_idTema = tc.idtema AND tc.idclase={$groupId})
			inner join grupo g on (p.fk_usuarioSocial_destino = g.fk_idusuarioSocial AND g.idgrupo={$groupId})
			INNER JOIN examen_sondeo as exs ON (exs.fk_examen_has_sondeo = e.idPost )
			WHERE p.idpost=e.idpost and p.enlace != 2 and e.tipoExamen='sondeo' AND exs.sondeo_disponible = '1'
			AND p.idpost NOT IN (SELECT tm.fk_idPost FROM post p INNER JOIN tcu_modulo tm ON tm.fk_idContenido = p.idpost WHERE p.fk_usuarioSocial_destino = (SELECT fk_idusuarioSocial FROM grupo WHERE idgrupo = {$groupId}))
			group by e.titulo)			
			union			
			(SELECT
			p.idpost as id,
			IF(t.titulo IS NULL OR t.titulo = '', p.contenido,t.titulo) as title,
			p.fk_idTema as parent_id, 'link' as link, 'file' as `type`, null as resourceType
			FROM (post as p, tarea t)
			inner join temas_clase tc on (p.fk_idTema = tc.idtema AND tc.idclase={$groupId})
			inner join grupo g on (p.fk_usuarioSocial_destino = g.fk_idusuarioSocial AND g.idgrupo={$groupId})
			WHERE p.idpost=t.fk_idpost
			AND t.respuestaAlumno IS NULL 
            AND p.idpost NOT IN (SELECT tm.fk_idPost FROM post p 
            INNER JOIN tcu_modulo tm ON tm.fk_idContenido = p.idpost 
            WHERE p.fk_usuarioSocial_destino = (SELECT fk_idusuarioSocial FROM grupo WHERE idgrupo = {$groupId}))
			group by p.idpost)				
			union			
			(SELECT p.idPost, p.contenido, p.fk_idTema as parent_id, 'link' as link, 'tcu' as `type`, tm.tipo as resourceType
			FROM (post as p, tcu_modulo tm, grupo g)
		  	inner join tcu_contenido tcc on (p.idPost=tcc.idContenido) 		  	
		  	WHERE fk_idTema IN(select idtema from temas_clase where idclase={$groupId})
			and tm.fk_idContenido = p.idPost
			AND p.fk_usuarioSocial_destino = g.fk_idusuarioSocial
		    AND (tcc.fechaDisponibleInicio<'2022-08-23 15:59:30' OR (tcc.fechaDisponibleInicio IS NULL OR tcc.fechaDisponibleInicio='0000-00-00 00:00:00'))
 			AND (ADDTIME(tcc.fechaDisponibleFin,'23:59:59')>='2022-08-23 15:59:30' OR (tcc.fechaDisponibleFin IS NULL OR tcc.fechaDisponibleFin='0000-00-00 00:00:00'))
			and g.idgrupo={$groupId})
			ORDER BY id            
        ";
        return $this->query($sql);
    }

}
